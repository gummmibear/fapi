<?php

namespace App\Tests\Command;

use App\Command\CsvImportCommand;
use App\CsvImport\CsvImport;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CsvImportCommandTest extends KernelTestCase
{
    const COMMAND_NAME = 'app:csv:import';
    const CSV_FILE_NAME = 'csv_file_name';

    /** @var Application */
    private $application;

    /** @var LoggerInterface | MockObject */
    private $loggerMock;
    /** @var CsvImport | MockObject */
    private $csvImportMock;

    public function setUp()
    {
        $kernel = self::bootKernel();
        $this->application = new Application($kernel);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->csvImportMock = $this->createMock(CsvImport::class);

        $this->application->add(new CsvImportCommand($this->loggerMock, $this->csvImportMock));
    }

    public function testExecute_ShouldLogAndSaveFlights()
    {
        //given
        $command = $this->application->find(static::COMMAND_NAME);

        //expect
        $this->loggerMock
            ->expects($this->exactly(2))
            ->method('info')
            ->withConsecutive(
                ['Start importing CSV file'],
                ['Importing CSV file completed']
            );

        $this->csvImportMock->method('withFile')
            ->with(self::CSV_FILE_NAME)
            ->willReturnSelf();

        $this->csvImportMock->expects($this->once())->method('save');

        //when
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
            CsvImportCommand::CSV_FILE_PATH_ARGUMENT => static::CSV_FILE_NAME
        ]);

        //then
    }
}