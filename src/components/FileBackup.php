<?php

namespace luya\backup\components;

use luya\backup\components\schema\ISchema;
use luya\backup\schedules\FileJob;
use luya\Exception;

/**
 * Class FileBackup
 *
 * @property FileJob $job
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class FileBackup extends BaseBackup
{
    const SCHEMAS = [
        'file' => schema\File::class,
        'ftp' => schema\Ftp::class
    ];

    protected $jobPath;

    public function __construct(FileJob $job, string $exportDir)
    {
        parent::__construct($job, $exportDir);

        $this->setJobPath($this->job->path);
    }

    public function setJobPath(string $jobPath)
    {
        if (($pos = strpos($jobPath, '://')) !== false) {
            $jobPath = substr_replace($jobPath, '', 0, $pos + 3);
        }
        $this->jobPath = \Yii::getAlias($jobPath);
    }

    protected function createSchema(string $jobPath, string $exportDir): ISchema
    {
        $schema = strstr($jobPath, '://', true) ?: 'file';
        $schemaClass = self::SCHEMAS[$schema];

        if (!is_subclass_of($schemaClass, ISchema::class)) {
            throw new \RuntimeException('Class ' . $schemaClass . ' must be a typeof ' . ISchema::class);
        }

        return \Yii::createObject($schemaClass, [$jobPath, $exportDir]);
    }

    /**
     * @throws Exception
     */
    public function createBackup()
    {
        $exportDir = $this->getExportDir();

        $schema = $this->createSchema($this->jobPath, $exportDir);

        list($sourceDir, $sourceTarget) = $schema->initSource();

        $tmpArchive = $schema->createArchive($sourceDir, $sourceTarget);

        $timestamp = date('Ymd_His');
        $basename = basename($this->jobPath);
        $destinationPath = $exportDir . "/{$basename}_{$timestamp}.tar.gz";

        $schema->uploadFile($tmpArchive, $destinationPath);
        $this->job->last_run = time();

        $message = \Yii::t(
            'app',
            'Dateipfad {source} wurde erfolgreich im folgenden Pfad abgelegt: {destinationPath}',
            ['source' => $sourceDir . '/' . $sourceTarget, 'destinationPath' => $destinationPath],
            'de_DE'
        );
        $this->log($message);

        $keepLastNBackups = 48;
        $schema->cleanUp($keepLastNBackups);

        $this->success(\Yii::t('app', "Erfolgreich abgeschlossen", [], 'de_DE'));
    }
}