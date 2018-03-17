<?php

namespace luya\backup\controllers;

/**
 * Job Controller.
 * 
 * File has been created with `crud/create` command. 
 */
class DbJobController extends \luya\admin\ngrest\base\Controller
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'luya\backup\models\DbJob';
}