<?php

declare(strict_type=1);

namespace App\Test\Mongo\Document;

use App\Mongo\Document\FlightPrice;
use PHPUnit\Framework\TestCase;

class FlightPriceTest extends TestCase
{
    public function testFlightPrice()
    {
        //given
        $externalId = 666;
        $price = 1234.56;
        $currency = 'THB';

        //when
        $sut = new FlightPrice($externalId, $price, $currency);
        $result = $sut->toArray();

        //then
        $expectedResult = [
            'externalId' => $externalId,
            'price' => $price,
            'currency' => $currency
        ];

        $this->assertEquals($expectedResult, $result);
    }
}