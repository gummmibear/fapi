<?php

declare(strict_types=1);

namespace App\Mongo\Collection;

use App\Mongo\Document\FlightPrice;
use App\Mongo\Exception\NotFoundException;

class FlightPriceCollection extends AbstractCollection
{
    const COLLECTION_NAME = 'flightPrice';

    public function findOneBy($query):FlightPrice
    {
        $document = $this->getCollection()->findOne($query);

        if (!$document) {
            throw new NotFoundException(implode($query));
        }

        return new FlightPrice(
            $document->externalId,
            $document->price,
            $document->currency
        );
    }
}