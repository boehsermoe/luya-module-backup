<?php

namespace luya\backup\apis;

/**
 * Job Controller.
 * 
 * File has been created with `crud/create` command. 
 */
class FileJobController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\backup\models\FileJob';
}