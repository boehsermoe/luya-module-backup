<?php

namespace luya\backup\components\schema;

use yii\base\Configurable;

interface ISchema extends Configurable
{
    public function __construct(string $jobPath, string $exportDir);

    public function initSource(): array;

    public function cleanUp(int $keepLastNBackups): void;

    public function createArchive(string $sourceDir, string $sourceTarget): string;

    public function uploadFile(string $fromPath, string $toPath): void;

    public function exec(string $command, array &$output = null, &$return_var = null);
}