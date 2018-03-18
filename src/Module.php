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
}