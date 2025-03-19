<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Radio;
use Generator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use stdClass;

class RadioFilesFolder
{
    public function __construct(
        #[Autowire(env: 'RADIO_FILES_FOLDER')]
        private string $baseFolder = '',
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

    public function deleteFiles(Radio $radio, string $fileName): void
    {
        $radioFolder = $this->getRadioFolder($radio);
        if (!is_dir($radioFolder)) {
            return;
        }

        foreach (['json', 'wav', 'mp3'] as $extension) {
            $filePath = $radioFolder . DIRECTORY_SEPARATOR . str_replace('.json', '.' . $extension, $fileName);
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }
    }

    private function getRadioFolder(Radio $radio): string
    {
        return $this->baseFolder . DIRECTORY_SEPARATOR . $radio->getFolderName();
    }


}