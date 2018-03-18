<?php

namespace luya\backup\schedules;

use luya\backup\components\DbBackup;
use luya\backup\Module;
use luya\scheduler\models\BaseJob;

class DatanbaseBackupJob extends BaseJob
{
	public $mysqlHost;

    public $mysqlDatabaseName;

    public $mysqlUserName;

    public $mysqlPassword;

	public function extraFields()
	{
		return [
			'mysqlHost',
			'mysqlDatabaseName',
			'mysqlUserName',
			'mysqlPassword',
		];
	}

	public function ngrestExtraAttributeTypes()
	{
		return [
			'mysqlHost' => 'text',
			'mysqlDatabaseName' => 'text',
			'mysqlUserName' => 'text',
			'mysqlPassword' => 'text',
		];
	}

	public function run()
	{
		$exportDir = Module::getInstance()->exportDir;

		$handler = new DbBackup($this, $exportDir);
		$handler->createBackup();
	}
}