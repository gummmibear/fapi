<?php

declare(strict_types=1);

namespace App\Test\Mongo\Document;

use App\Mongo\Document\DocumentCollection;
use App\Mongo\Document\DocumentInterface;
use PHPUnit\Framework\TestCase;

class DocumentCollectionTest extends TestCase
{
    public function testGetDocuments_ShouldReturnEmptyArray_IfNoDocumentAdded()
    {
        //when
        $sut = new DocumentCollection();
        //then
        $this->assertEmpty($sut->getDocuments());
    }

    public function testAddDocument_ShouldAddDocumentToCollection()
    {
        //given
        $documentMock = $this->createMock(DocumentInterface::class);

        //when
        $sut = new DocumentCollection();
        $sut->addDocument($documentMock);

        //then
        $this->assertNotEmpty($sut->getDocuments());
        $this->assertContains($documentMock, $sut->getDocuments());
    }

    public function testAddDocuments_ShouldAddDocumentsToCollection()
    {
        // given
        $documentMockFirst = $this->createMock(DocumentInterface::class);
        $documentMockSecond = $this->createMock(DocumentInterface::class);


        // when
        $sut = new DocumentCollection();
        $sut->addDocuments([$documentMockFirst, $documentMockSecond]);

        //then
        $this->assertNotEmpty($sut->getDocuments());
        $this->assertCount(2, $sut->getDocuments());
    }

    public function testToArray_ShouldReturnArray()
    {
        //given
        $firstArray = ['firstArray'];
        $documentMockFirst = $this->createMock(DocumentInterface::class);
        $documentMockFirst
            ->method('toArray')
            ->willReturn($firstArray);

        $secondArray = ['secondArray'];
        $documentMockSecond = $this->createMock(DocumentInterface::class);
        $documentMockSecond
            ->method('toArray')
            ->willReturn($secondArray);

        //when
        $sut = new DocumentCollection();
        $sut->addDocuments([$documentMockFirst, $documentMockSecond]);

        //then
        $expectedResult = [
            $firstArray,
            $secondArray
        ];

        $result = $sut->toArray();
        $this->assertEquals($expectedResult, $result);
    }
}