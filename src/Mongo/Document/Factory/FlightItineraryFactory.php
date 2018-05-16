<?php

declare(strict_types=1);

namespace App\Mongo\Document\Factory;

use App\CsvImport\CsvLineKeyMap;
use App\Mongo\Document\DocumentCollection;
use App\Mongo\Document\FlightItinerary;

class FlightItineraryFactory
{
    public function createFromArray(array $flights): DocumentCollection
    {
        $collection = new DocumentCollection();

        foreach ($flights as $flight) {
            $collection->addDocument(new FlightItinerary(
                    $flight[CsvLineKeyMap::KEY_EXTERNAL_ID],
                    $flight[CsvLineKeyMap::KEY_FLIGHT_NUMBER],
                    $flight[CsvLineKeyMap::KEY_FROM],
                    $flight[CsvLineKeyMap::KEY_TO],
                    $flight[CsvLineKeyMap::KEY_DATE_START],
                    $flight[CsvLineKeyMap::KEY_DATE_END]
                )
            );
        }

        return $collection;
    }
}