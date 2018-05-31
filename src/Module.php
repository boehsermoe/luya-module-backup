<?php

namespace luya\backup;

use luya\backup\schedules\DbJob;
use luya\backup\schedules\FileJob;

/**
 * Backup Admin Module.
 *
 * File has been created with `module/create` command.
 *
 * @property $exportDir The path where the backup files will be exported. Default @runtime/backups
 */
class Module extends \luya\admin\base\Module
{
    public $exportDir = '@runtime/backups';
}