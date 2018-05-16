<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Controller\HealthCheckController;
use PHPUnit\Framework\TestCase;

class HealthCheckControllerTest extends TestCase
{
    /** @var HealthCheckController */
    private $sut;

    public function setUp()
    {
        $this->sut = new HealthCheckController();
    }

    public function testCheck_ShouldReturnNotEmptyJsonResponse()
    {
        $result = $this->sut->check();

        $this->assertNotEmpty($result->getContent());
    }
}