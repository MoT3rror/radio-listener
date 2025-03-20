<?php

namespace App\Command;

use App\Entity\AudioFile;
use App\Entity\Radio;
use App\Entity\Recording;
use App\Repository\RadioRepository;
use App\Service\CustomFileInfo;
use App\Service\RadioFilesFolder;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Uploadable\FileInfo\FileInfoArray;
use Gedmo\Uploadable\UploadableListener;
use SplFileInfo;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:import-radio-files',
    description: 'Import radio files from folder and convert WAV to MP3',
)]
class ImportRadioFilesCommand extends Command
{
    public function __construct(
        private RadioFilesFolder $radioFilesFolder,
        private RadioRepository $radioRepository,
        private EntityManagerInterface $entityManager,
        private UploadableListener $uploadableListener,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $radios = $this->radioRepository->findAll();
        
        foreach ($radios as $radio) {
            foreach ($this->radioFilesFolder->getJsonFiles($radio) as $file) {
                $json = $this->radioFilesFolder->getJson($radio, $file);
                $wavFilePath = $this->radioFilesFolder->getWavFilePath($radio, str_replace('.json', '.wav', $file));

                $mp3FilePath = $this->convertWavToMp3($wavFilePath, $output);
                
                $recording = new Recording;
                
                $recording->setStartTime(new DateTimeImmutable($json->start_time, new DateTimeZone('America/Chicago')));
                $recording->setEndTime(new DateTimeImmutable($json->end_time, new DateTimeZone('America/Chicago')));
                $recording->setRadio($radio);

                $audioFile = new AudioFile;
                
                $this->uploadableListener->addEntityFileInfo($audioFile, new CustomFileInfo($mp3FilePath));
                $this->entityManager->persist($audioFile);
                
                $recording->setAudioFile($audioFile);
                
                $this->entityManager->persist($recording);
                $this->entityManager->flush();

                $this->radioFilesFolder->deleteFiles($radio, $file);
            }
        }

        return Command::SUCCESS;
    }

    private function convertWavToMp3(string $wavFilePath, OutputInterface $output): string
    {
        $helper = $this->getHelper('process');
        
        $mp3FilePath = str_replace('.wav', '.mp3', $wavFilePath);
        $command = 'lame -V 5 ' . $wavFilePath . ' ' . $mp3FilePath;
        $process = new Process(explode(' ', $command));

        $helper->run($output, $process);

        return $mp3FilePath;
    }
}
