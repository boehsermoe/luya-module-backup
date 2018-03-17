<?php

namespace luya\backup\models;

use luya\backup\components\DbBackup;
use luya\backup\Module;
use luya\scheduler\models\BaseJob;

class DbJob extends BaseJob
{
	public $mysqlHost;

    public $mysqlDatabaseName;

    public $mysqlUserName;

    public $mysqlPassword;

	/**
	 * @inheritdoc
	 */
	public static function ngRestApiEndpoint()
	{
		return 'api-backup-db-job';
	}

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