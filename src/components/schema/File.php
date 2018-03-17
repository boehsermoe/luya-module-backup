<?php

namespace luya\backup\components\schema;

use luya\Exception;
use yii\base\BaseObject;
use yii\base\InvalidParamException;

class File implements ISchema
{
	protected $jobPath;
	protected $exportDir;

	public function __construct(string $jobPath, string $exportDir)
	{
		$this->jobPath = $jobPath;
		$this->exportDir = $exportDir;
	}

	/**
	 * @return array
	 */
	public function initSource(): array
	{
		$jobPath = \Yii::getAlias($this->jobPath);

		$sourceDir = realpath(dirname($jobPath));
		$sourceTarget = basename($jobPath);
		$sourcePath = $sourceDir . DIRECTORY_SEPARATOR . $sourceTarget;

		if (!file_exists($sourcePath)) {
			throw new InvalidParamException("The path $sourcePath not exists.");
		}

		return array($sourceDir, $sourceTarget);
	}

	/**
	 * @param $keepLastNBackups
	 */
	public function cleanUp(int $keepLastNBackups): void
	{
		$count = $this->exec("ls -1t {$this->exportDir}* | tail -n +{$keepLastNBackups} | wc -l");
		$count = intval($count);

		if ($count > 0) {
			$this->log("Entferne alte Backups...");
			exec("ls -1t {$this->exportDir}/* | tail -n +{$keepLastNBackups} | xargs rm");
		}
	}

	public function createArchive(string $sourceDir, string $sourceTarget): string
	{
		$tempPath = tempnam(sys_get_temp_dir(),  'backup_');

		$command = "tar -v -cjf $tempPath -C $sourceDir $sourceTarget";
		$this->exec($command, $output, $exitCode);

		if ($exitCode > 0) {
			throw new Exception("Could not create archive ({$tempPath}) of {$sourceDir}/{$sourceTarget}.\n" . implode(PHP_EOL, $output));
		}

		return $tempPath;
	}

	public function uploadFile(string $fromPath, string $toPath): void
	{
		$moved = rename($fromPath, $toPath);

		if ($moved === false) {
			throw new Exception("File could not moved from {$fromPath} to {$toPath}.\n");
		}
	}

	public function exec(string $command, array &$output = null, &$return_var = null)
	{
		\Yii::trace($command);

		return exec($command . ' 2>&1', $output, $return_var);
	}
}