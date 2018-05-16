<?php

namespace App\Mongo\Collection;

use App\Mongo\Document\DocumentCollection;
use App\Mongo\Document\DocumentInterface;
use App\Mongo\Exception\MongoWriteException;
use MongoDB\Client;
use MongoDB\Collection;
use MongoDB\Driver\Exception\WriteException;

abstract class AbstractCollection
{
    const COLLECTION_NAME = '';

    private $client;

    public function __construct(Client $client, string $dbsName)
    {
        $this->client = $client;
        $this->dbsName = $dbsName;
    }

    public function insertOne(DocumentInterface $document):DocumentInterface
    {

        try {
            $this->getCollection()->insertOne($document->toArray());
        } catch (WriteException $exception) {
            throw new MongoWriteException('', $exception);
        }
    }

    public function insertMany(DocumentCollection $documentCollection)
    {
        try {
            $this->getCollection()->insertMany($documentCollection->toArray());
        } catch (WriteException $exception) {
            throw new MongoWriteException('', $exception);
        }
    }

    public function getCollection():Collection
    {
        return $this->client->selectCollection($this->dbsName, static::COLLECTION_NAME);
    }
}