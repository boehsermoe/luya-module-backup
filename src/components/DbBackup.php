<?php

namespace luya\backup\components;

use luya\backup\models\DbJob;
use yii\db\Exception;

/**
 * Class DbBackup
 *
 * @property DbJob $job
 *
 * @author Bennet Klarhoelter <boehsermoe@me.com>
 */
class DbBackup extends BaseBackup
{
    public function __construct(DbJob $job, string $exportDir)
    {
        parent::__construct($job, $exportDir);
    }

    public function createBackup()
    {
        $this->createDump();
    }

    private function createDump()
    {
        $mysqlHostName = $this->job->mysqlHost;

        $exportDir = $this->getExportDir();
        $exportDirPrefix = $exportDir . '/' .$this->job->name . '_';
        $mysqlExportPath = $exportDirPrefix . date('Ymd_His') . '.sql';

        //Bei den folgenden Punkten bitte keine Änderung durchführen
        //Export der Datenbank und Ausgabe des Status
        $command = 'mysqldump --opt -h' . $mysqlHostName .' -u' . $this->job->mysqlUserName .' -p' . $this->job->mysqlPassword .' ' . $this->job->mysqlDatabaseName .' | gzip > ' . $mysqlExportPath . '.gz';
        $this->exec($command, $output, $worked);

        switch($worked){
            case 0:
                $this->log('Die Datenbank ' . $this->job->mysqlDatabaseName .' wurde erfolgreich im folgenden Pfad abgelegt ~/' . $mysqlExportPath);

                $keepLastNBackups = 48;
                $this->cleanUp($keepLastNBackups);
	            $this->success("Erfolgreich abgeschlossen");

                break;
            case 1:
	            $this->error('Es ist ein Fehler aufgetreten beim Exportieren von ' . $this->job->mysqlDatabaseName .' zu ~/' . $mysqlExportPath);

	            // Todo: Bessere Fehlermeldung
	            throw new Exception('Es ist ein Fehler beim Exportieren aufgetreten.');
                break;
            case 2:

	            $this->error('Es ist ein Verbindungsfehler aufgetreten, bitte prüfen Sie die folgenden Angaben: ');
	            $this->error('MySQL Database: ' . $this->job->mysqlDatabaseName);
	            $this->error('MySQL User: ' . $this->job->mysqlUserName);
	            $this->error('MySQL Host: ' . $this->job->mysqlHost);

	            // Todo: Bessere Fehlermeldung
	            throw new Exception('Es ist ein Verbindungsfehler aufgetreten.');
	            break;
        }
    }

}