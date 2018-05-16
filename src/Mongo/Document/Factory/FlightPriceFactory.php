<?php

declare(strict_types=1);

namespace App\Mongo\Document\Factory;

use App\Mongo\Document\FlightPrice;
use App\CsvImport\CsvLineKeyMap;

class FlightPriceFactory
{
    public function createFromArray(array $price): FlightPrice
    {
        return new FlightPrice(
            $price[CsvLineKeyMap::KEY_EXTERNAL_ID],
            (float) $price[CsvLineKeyMap::KEY_PRICE],
            $price[CsvLineKeyMap::KEY_CURRENCY]
        );
    }
}