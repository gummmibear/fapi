<?php

declare(strict_types=1);

namespace App\Mongo\Document;

class FlightPrice implements DocumentInterface
{
    private $externalId;
    private $price;
    private $currency;

    public function __construct(string $externalId, float $price, string $currency)
    {
        $this->externalId = $externalId;
        $this->price = $price;
        $this->currency = $currency;
    }

    public function toArray():array
    {
        return [
            'externalId' => $this->externalId,
            'price' => $this->price,
            'currency' => $this->currency,
        ];
    }
}