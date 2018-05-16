<?php

declare(strict_types=1);

namespace App\Mongo\Document\Factory;

use App\CsvImport\CsvLineKeyMap;
use App\Mongo\Document\FlightInfo;

class FlightInfoFactory
{
    public function createFromAirports(array $firstAirport, array $lastAirport): FlightInfo
    {
        return new FlightInfo(
            $firstAirport[CsvLineKeyMap::KEY_EXTERNAL_ID],
            $firstAirport[CsvLineKeyMap::KEY_FLIGHT_NUMBER],
            $firstAirport[CsvLineKeyMap::KEY_FROM],
            $lastAirport[CsvLineKeyMap::KEY_TO],
            $firstAirport[CsvLineKeyMap::KEY_DATE_START],
            $lastAirport[CsvLineKeyMap::KEY_DATE_END]
        );
    }
}