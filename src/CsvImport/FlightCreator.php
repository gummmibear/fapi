<?php

declare(strict_types=1);

namespace App\CsvImport;

use App\Mongo\Collection\FlightInfoCollection;
use App\Mongo\Collection\FlightItineraryCollection;
use App\Mongo\Collection\FlightPriceCollection;
use App\Mongo\Document\DocumentCollection;
use App\Mongo\Document\Factory\FlightInfoFactory;
use App\Mongo\Document\Factory\FlightItineraryFactory;
use App\Mongo\Document\Factory\FlightPriceFactory;
use App\Mongo\Document\FlightInfo;
use App\Mongo\Document\FlightPrice;

class FlightCreator
{
    private $flightInfoCollection;
    private $flightInfoFactory;
    private $flightItineraryFactory;
    private $flightItineraryCollection;
    private $flightPriceFactory;
    private $flightPriceCollection;

    public function __construct(
        FlightInfoFactory $flightInfoFactory,
        FlightInfoCollection $flightInfoCollection,
        FlightItineraryFactory $flightItineraryFactory,
        FlightItineraryCollection $flightItineraryCollection,
        FlightPriceFactory $priceFactory,
        FlightPriceCollection $priceCollection
    )
    {
        $this->flightInfoFactory = $flightInfoFactory;
        $this->flightItineraryFactory = $flightItineraryFactory;
        $this->flightInfoCollection = $flightInfoCollection;
        $this->flightItineraryCollection = $flightItineraryCollection;
        $this->flightPriceCollection = $priceCollection;
        $this->flightPriceFactory = $priceFactory;
    }

    public function saveBatch(array $flights): void
    {
        $flightInfoCollection = new DocumentCollection();
        $flightPriceCollection = new DocumentCollection();
        $flightItineraryCollection = new DocumentCollection();

        foreach ($flights as $externalId => [CsvImport::FLIGHTS_KEY => $flights, CsvImport::PRICE_KEY => $price]) {
            $flightInfo = $this->createFlightInfo($flights);
            $flightItinerary = $this->createFlightItinerary($flights);
            $price = $this->createFlightPrice($price);

            $flightInfoCollection->addDocument($flightInfo);
            $flightPriceCollection->addDocument($price);
            $flightItineraryCollection->addDocuments($flightItinerary->getDocuments());
        }

        $this->saveCollections($flightInfoCollection, $flightPriceCollection, $flightItineraryCollection);
    }

    private function createFlightInfo(array $flights): FlightInfo
    {
        return $this->flightInfoFactory->createFromAirports(reset($flights), end($flights));
    }

    private function createFlightItinerary(array $flights): DocumentCollection
    {
        return $this->flightItineraryFactory->createFromArray($flights);
    }

    private function createFlightPrice(array $price): FlightPrice
    {
        return $this->flightPriceFactory->createFromArray($price);
    }

    private function saveCollections(
        DocumentCollection $flightInfoCollection,
        DocumentCollection $flightPriceCollection,
        DocumentCollection $flightItineraryCollection
    ): void
    {
        $this->flightInfoCollection->insertMany($flightInfoCollection);
        $this->flightPriceCollection->insertMany($flightPriceCollection);
        $this->flightItineraryCollection->insertMany($flightItineraryCollection);
    }
}