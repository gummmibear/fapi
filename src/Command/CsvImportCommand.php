<?php

declare(strict_types=1);

namespace App\Command;

use App\CsvImport\CsvImport;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CsvImportCommand extends Command
{
    const CSV_FILE_PATH_ARGUMENT = 'csvfile';

    protected static $defaultName = 'app:csv:import';

    private $logger;
    private $csvImport;

    public function __construct(LoggerInterface $logger, CsvImport $csvImport)
    {
        $this->logger = $logger;
        $this->csvImport = $csvImport;

        parent::__construct();
    }

    protected function configure():void
    {
        $this
            ->setDescription('Csv import Command')
            ->addArgument(static::CSV_FILE_PATH_ARGUMENT, InputArgument::REQUIRED, 'file path to csv');
    }

    protected function execute(InputInterface $input, OutputInterface $output):void
    {
        $this->logger->info('Start importing CSV file');

        $file = $input->getArgument(static::CSV_FILE_PATH_ARGUMENT);

        $this->csvImport->withFile($file)->save();

        $this->logger->info('Importing CSV file completed');
    }
}
