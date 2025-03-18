<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Radio;
use Generator;
use stdClass;

class RadioFilesFolder
{
    public function __construct(
        private string $baseFolder,
    )
    {}

    public function getJsonFiles(Radio $radio): Generator
    {
        $radioFolder = $this->getRadioFolder($radio);
        if (!is_dir($radioFolder)) {
            return;
        }

        $files = scandir($radioFolder);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'json') {
                yield $file;
            }
        }
    }

    public function getJson(Radio $radio, string $fileName): ?stdClass
    {
        $radioFolder = $this->getRadioFolder($radio);
        $filePath = $radioFolder . DIRECTORY_SEPARATOR . $fileName;
        if (!is_file($filePath)) {
            return null;
        }

        return json_decode(file_get_contents($filePath));
    }

    public function getWavFilePath(Radio $radio, string $fileName): string
    {
        $radioFolder = $this->getRadioFolder($radio);
        return $radioFolder . DIRECTORY_SEPARATOR . $fileName;
    }

    private function getRadioFolder(Radio $radio): string
    {
        return $this->baseFolder . DIRECTORY_SEPARATOR . $radio->getFolderName();
    }
}