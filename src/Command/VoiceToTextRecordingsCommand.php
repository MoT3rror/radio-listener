<?php

namespace App\Command;

use App\Repository\RecordingRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

#[AsCommand(
    name: 'app:voice-to-text-recordings',
    description: 'MP3 to text recordings',
)]
class VoiceToTextRecordingsCommand extends Command
{
    public function __construct(
        private RecordingRepository $recordingRepository,
        private EntityManagerInterface $entityManager
    )
    {
        parent::__construct();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $limit = 10;
        $count = 0;

        $recordings = $this->recordingRepository->findRecordingsWithNullVoiceToText();
        foreach ($recordings as $recording) {
            $output->writeln((new DateTime())->format(DateTime::ATOM) . 'Recording ID: ' . $recording->getId());

            $mp3FilePath = $recording->getAudioFile()->getPath();
            $voiceToText = $this->convertMp3ToText($mp3FilePath, $output);
            $recording->setVoiceToText($voiceToText);

            $this->entityManager->flush();
            $count++;
            if ($count >= $limit) {
                return Command::SUCCESS;
            }
        }

        $output->writeln((new DateTime())->format(DateTime::ATOM) . 'All recordings have been processed.');

        return Command::SUCCESS;
    }

    public function convertMp3ToText(string $mp3FilePath, OutputInterface $output): string
    {
        $helper = $this->getHelper('process');
        
        $command = 'whisper --model small --language English ' . $mp3FilePath . ' --fp16 False --output_dir var/whisper';
        $process = new Process(explode(' ', $command));
        $process->setTimeout(120);

        $helper->run($output, $process);

        return $process->getOutput();
    }
}
