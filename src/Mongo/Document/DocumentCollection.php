<?php

declare(strict_types=1);

namespace App\Mongo\Document;

class DocumentCollection
{
    private $documents = [];

    public function addDocument(DocumentInterface $document)
    {
        $this->documents[] = $document;
    }

    public function addDocuments(array $documents)
    {
        $this->documents = array_merge($this->documents, $documents);
    }

    public function getDocuments():array
    {
        return $this->documents;
    }

    public function toArray():array
    {
        $documents =[];

        foreach ($this->documents as $document) {
            $documents[] = $document->toArray();
        }

        return $documents;
    }
}