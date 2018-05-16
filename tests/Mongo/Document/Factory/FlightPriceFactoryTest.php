<?php

declare(strict_types=1);

namespace app\tests\Mongo\Document\Factory;

use App\CsvImport\CsvLineKeyMap;
use App\Mongo\Document\Factory\FlightPriceFactory;
use App\Mongo\Document\FlightPrice;
use PHPUnit\Framework\TestCase;

class FlightPriceFactoryTest extends TestCase
{
    /** @var FlightPriceFactory */
    private $sut;

    public function setUp()
    {
        $this->sut = new FlightPriceFactory();
    }

    public function testFlightPriceFactory()
    {
        //given
        $externalId = '123';
        $price = 123.12;
        $currency = 'THB';

        $priceArray = [
            CsvLineKeyMap::KEY_EXTERNAL_ID => $externalId,
            CsvLineKeyMap::KEY_PRICE => $price,
            CsvLineKeyMap::KEY_CURRENCY => $currency
        ];

        //when
        $result = $this->sut->createFromArray($priceArray);

        //then
        $this->assertInstanceOf(FlightPrice::class, $result);

        $expectedResult =  [
            'externalId' => $externalId,
            'price' => $price,
            'currency' => $currency
        ];

        $this->assertEquals($expectedResult, $result->toArray());
    }
}