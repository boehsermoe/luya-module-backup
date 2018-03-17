<?php

namespace luya\backup\models;

use luya\backup\components\FileBackup;
use luya\backup\Module;
use luya\scheduler\models\BaseJob;

/**
 * Class FileJob
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 *
 * @property string $path
 *
 */
class FileJob extends BaseJob
{
	public $path;

	/**
	 * @inheritdoc
	 */
	public static function ngRestApiEndpoint()
	{
		return 'api-backup-file-job';
	}

	public function rules()
	{
		return array_merge(parent::rules(), [
			[['path'], 'required']
		]);
	}


	public function extraFields()
	{
		return [
			'path'
		];
	}

	public function ngrestExtraAttributeTypes()
	{
		return [
			'path' => 'text',
		];
	}

	public function run()
	{
		$exportDir = Module::getInstance()->exportDir;

		$handler = new FileBackup($this, $exportDir);
		$handler->createBackup();
	}
}