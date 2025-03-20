<?php

namespace App\Command;

use App\Entity\AudioFile;
use App\EventListener\AudioFilePostFlushListener;
use App\Repository\AudioFileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:fixAudioFilePath',
    description: 'Add a short description for your command',
)]
class FixAudioFilePathCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AudioFileRepository $audioFileRepository,
        private AudioFilePostFlushListener $audioFilePostFlushListener,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $audioFiles = $this->audioFileRepository->findAll();
        foreach ($audioFiles as $audioFiles) {
            $this->audioFilePostFlushListener->postPersist($audioFiles);
        }
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
