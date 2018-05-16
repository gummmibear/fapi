<?php

declare(strict_types=1);

namespace App\Mongo\Document;

class FlightInfo implements DocumentInterface
{
    private $externalId;
    private $flightNumber;
    private $from;
    private $to;
    private $dateStart;
    private $dateEnd;

    public function __construct(string $externalId, string $flightNumber, string $from, string $to, string $dateStart, string $dateEnd)
    {
        $this->externalId = $externalId;
        $this->flightNumber = $flightNumber;
        $this->from = $from;
        $this->to = $to;
        $this->dateStart = $dateStart;
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'externalId' => $this->externalId,
            'flightNumber' => $this->flightNumber,
            'from' => $this->from,
            'to' => $this->to,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd
        ];
    }
}