<?php

namespace luya\backup;

use luya\backup\components\DbBackup;
use luya\backup\components\FileBackup;
use luya\backup\schedules\DbJob;
use luya\backup\schedules\FileJob;
use luya\scheduler\models\BaseJob;
use yii\base\InvalidConfigException;

/**
 * Backup Admin Module.
 *
 * File has been created with `module/create` command. 
 */
class Module extends \luya\admin\base\Module
{
	public $exportDir = '@runtime/backups';

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