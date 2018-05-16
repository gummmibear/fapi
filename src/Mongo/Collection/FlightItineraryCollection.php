<?php

declare(strict_types=1);

namespace App\Mongo\Collection;

use App\Mongo\Document\DocumentCollection;
use App\Mongo\Document\FlightItinerary;

class FlightItineraryCollection extends AbstractCollection
{
    const COLLECTION_NAME = 'flightItinerary';

    public function findBy($query): DocumentCollection
    {
        $cursor = $this->getCollection()->find($query);

        $documentCollection = new DocumentCollection();

        foreach ($cursor as $doc) {
            $documentCollection->addDocument((new FlightItinerary(
                $doc->externalId,
                $doc->flightNumber,
                $doc->from,
                $doc->to,
                $doc->dateStart,
                $doc->dateEnd
            )));
        }

        return $documentCollection;
    }
}