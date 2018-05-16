<?php

declare(strict_types=1);

namespace App\Mongo\Collection;

use App\Mongo\Document\FlightInfo;
use App\Mongo\Exception\NotFoundException;

class FlightInfoCollection extends AbstractCollection
{
    const COLLECTION_NAME = 'flightInfo';

    public function findOneBy(array $query):FlightInfo
    {
        $document = $this->getCollection()->findOne($query);

        if (!$document) {
            throw new NotFoundException();
        }

        return new FlightInfo(
                $document->externalId,
                $document->flightNumber,
                $document->from,
                $document->to,
                $document->dateStart,
                $document->dateEnd
        );
    }
}
