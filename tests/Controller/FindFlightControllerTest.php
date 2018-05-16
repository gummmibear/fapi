<?php

namespace App\Test\Controller;

use App\Controller\FindFlightController;
use App\Mongo\Collection\FlightInfoCollection;
use App\Mongo\Document\FlightInfo;
use App\Mongo\Exception\NotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

class FindFlightControllerTest extends TestCase
{
    /** @var MockObject | FlightInfoCollection */
    private $flightInfoCollectionMock;

    /** @var FindFlightController */
    private $sut;

    public function setUp()
    {
        $this->flightInfoCollectionMock = $this->createMock(FlightInfoCollection::class);

        $this->sut = new FindFlightController($this->flightInfoCollectionMock);
    }

    public function testFind_ShouldReturnJsonResponse_IfFlightWasFound()
    {
        //given
        $externalId = '123';
        $flightNumber = '321';
        $from = 'ADB';
        $to = 'DEA';
        $dateStart = '2019-01-01 12:00:00';
        $dateEnd = '2019-01-01 16:00:00';

        $flightInfo = new FlightInfo($externalId, $flightNumber, $from, $to, $dateStart, $dateEnd);

        //expect
        $expectedQuery = ['from'=> $from, 'to'=>$to];

        $this->flightInfoCollectionMock->method('findOneBy')
            ->with($expectedQuery)
            ->willReturn($flightInfo);

        //when
        $result = $this->sut->find($from, $to);

        //then
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(JsonResponse::HTTP_OK, $result->getStatusCode());
        $this->assertEquals($flightInfo->toArray(), json_decode($result->getContent(), 1));
    }

    public function testFind_ShouldReturnJsonResponseWithNotFoundHttpCode_IfFlightWasNotFound()
    {
        //given
        $from = 'ADB';
        $to = 'DEA';

        //expect
        $expectedQuery = ['from'=> $from, 'to'=>$to];

        $this->flightInfoCollectionMock->method('findOneBy')
            ->with($expectedQuery)
            ->willThrowException(new NotFoundException());

        //when
        $result = $this->sut->find($from, $to);

        //then
        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals(JsonResponse::HTTP_NOT_FOUND, $result->getStatusCode());
        $this->assertEmpty(json_decode($result->getContent()));
    }
}