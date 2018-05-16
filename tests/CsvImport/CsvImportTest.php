<?php
/**
 * Created by PhpStorm.
 * User: gbear
 * Date: 16.05.18
 * Time: 22:15
 */

namespace App\Test\CsvImport;

use App\CsvImport\CsvImport;
use App\CsvImport\FlightCreator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CsvImportTest extends TestCase
{
    const CSV_FILE = __DIR__ . '/flightFixture.csv';

    /** @var CsvImport */
    private $sut;
    /** @var MockObject | FlightCreator */
    private $flightCreatorMock;

    public function setUp()
    {
        $this->flightCreatorMock = $this->createMock(FlightCreator::class);

        $this->sut = new CsvImport($this->flightCreatorMock);
    }

    public function testSave()
    {
        $this->flightCreatorMock
            ->expects($this->once())
            ->method('saveBatch');

        $this->sut
            ->withFile(static::CSV_FILE)
            ->save();
    }
}