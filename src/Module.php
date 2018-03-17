<?php

namespace luya\backup;

use luya\backup\components\DbBackup;
use luya\backup\components\FileBackup;
use luya\backup\models\DbJob;
use luya\backup\models\FileJob;
use luya\scheduler\models\BaseJob;
use yii\base\InvalidConfigException;

/**
 * Backup Admin Module.
 *
 * File has been created with `module/create` command. 
 */
class Module extends \luya\admin\base\Module
{
	public $apis = [
		'api-backup-file-job' => 'luya\backup\apis\FileJobController',
		'api-backup-db-job' => 'luya\backup\apis\DbJobController',
		'api-backup-execute-job' => 'luya\backup\apis\ExecuteJobController',
	];

	public $exportDir = '@runtime/backups';

	public function getMenu()
	{
		return (new \luya\admin\components\AdminMenuBuilder($this))
			->node('Backups', 'save')
			->group('Jobs')
			->itemApi('Filesystem', 'backup/file-job/index', 'label', 'api-backup-file-job')
			->itemApi('Database', 'backup/db-job/index', 'label', 'api-backup-db-job')
			;
	}

	/**
	 * @param BaseJob[] $jobs
	 * @param $exportDir
	 * @throws InvalidConfigException
	 */
	public function createBackup($jobs, $exportDir = null)
	{
		if (is_null($exportDir)) {
			$exportDir = $this->exportDir;
		}

		if (is_null($exportDir)) {
			throw new InvalidConfigException('Backup module required a $exportDir.');
		}

		foreach ($jobs as $job) {
			if ($job instanceof DbJob) {
				$handler = new DbBackup($job, $exportDir);
			}
			elseif ($job instanceof FileJob) {
				$handler = new FileBackup($job, $exportDir);
			}
			else {
				\Yii::info("The job {$job->name} ({$job->class}) could not handled.");
				continue;
			}

			$handler->createBackup();
		}
	}
}