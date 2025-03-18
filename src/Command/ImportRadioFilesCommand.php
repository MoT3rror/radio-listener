<?php

namespace App\Command;

use App\Repository\RadioRepository;
use App\Service\RadioFilesFolder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-radio-files',
    description: 'Add a short description for your command',
)]
class ImportRadioFilesCommand extends Command
{
    public function __construct(
        private RadioFilesFolder $radioFilesFolder,
        private RadioRepository $radioRepository,
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

                dd($json, $wavFilePath);
            }
        }

        return Command::SUCCESS;
    }
}
