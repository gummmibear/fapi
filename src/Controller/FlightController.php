<?php

declare(strict_types=1);

namespace App\Controller;

use App\Mongo\Collection\FlightInfoCollection;
use App\Mongo\Collection\FlightItineraryCollection;
use App\Mongo\Collection\FlightPriceCollection;
use App\Mongo\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;

class FlightController
{
    private $flightInfoCollection;
    private $flightItineraryCollection;
    private $flightPriceCollection;

    public function __construct(
        FlightInfoCollection $flightInfoCollection,
        FlightItineraryCollection $flightItineraryCollection,
        FlightPriceCollection $flightPriceCollection
    )
    {
        $this->flightInfoCollection = $flightInfoCollection;
        $this->flightItineraryCollection = $flightItineraryCollection;
        $this->flightPriceCollection = $flightPriceCollection;
    }

    public function get($externalId): JsonResponse
    {
        $query = ['externalId'=>$externalId];

        try {
            $flightInfo = $this->flightInfoCollection->findOneBy($query);
            $flightPrice = $this->flightPriceCollection->findOneBy($query);
            $flightItineraryCollection = $this->flightItineraryCollection->findBy($query);
        } catch (NotFoundException $exception) {
            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            'flightInfo' => $flightInfo->toArray(),
            'flightPrice' => $flightPrice->toArray(),
            'flightItinerary' => $flightItineraryCollection->toArray()
        ]);
    }
}