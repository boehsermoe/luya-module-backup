<?php

namespace luya\backup\components;

use luya\backup\models\Job;
use yii\helpers\Console;

abstract class BaseBackup
{
    /**
     * @var \luya\scheduler\models\BaseJob
     */
    protected $job;

    /**
     * @var string
     */
    private $exportDir;

    public function __construct(\luya\scheduler\models\BaseJob $job, string $exportDir)
    {
        $this->job = $job;
        $this->exportDir = $exportDir;
    }

    abstract public function createBackup();

	/**
     * @return string
     */
    protected function getExportDir(): string
    {
        $exportDir = \Yii::getAlias($this->exportDir) . '/' . $this->job->name;
        if (!file_exists($exportDir)) {
            mkdir($exportDir, 0777, true);
        }

	    return $exportDir;
    }

	/**
	 * @param $message
	 * @param array $format @see BaseConsole::ansiFormat
	 */
	protected function log($message, $format = [])
	{
		if (\Yii::$app->request->isConsoleRequest) {
			echo Console::ansiFormat($message . PHP_EOL, (array)$format);
		}

		\Yii::info($message);
	}

	protected function success($message)
	{
		$this->log($message, Console::FG_GREEN);
	}

	protected function error($message)
	{
		$this->log($message, Console::FG_RED);
	}
}