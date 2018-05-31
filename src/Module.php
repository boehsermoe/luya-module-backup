<?php

namespace luya\backup;

use luya\backup\schedules\DbJob;
use luya\backup\schedules\FileJob;

/**
 * Backup Admin Module.
 *
 * File has been created with `module/create` command.
 */
class Module extends \luya\admin\base\Module
{
    public $exportDir = '@runtime/backups';
}