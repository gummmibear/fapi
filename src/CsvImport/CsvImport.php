<?php

declare(strict_types=1);

namespace App\CsvImport;

class CsvImport
{
    const BATCH_SIZE = 100;
    const READ_FILE_MODE = 'r';
    const FLIGHTS_KEY = 'flights';
    const PRICE_KEY = 'price';

    /** @var \SplFileObject */
    private $file;

    private $flightCreator;

    public function __construct(FlightCreator $flightCreator)
    {
        $this->flightCreator = $flightCreator;
    }

    public function withFile(string $file): self
    {
        try {
            $this->file = new \SplFileObject($file);
            $this->file->setFlags(\SplFileObject::READ_CSV);
        } catch (\Throwable $exception) {
            throw new \RuntimeException('Cant open file');
        }

        return $this;
    }

    public function save(): void
    {
        $this->saveFlights();
    }

    private function saveFlights(): void
    {
        $flights = [];
        $tempFlight = [];

        $iterator = new \NoRewindIterator($this->iteratecsv());
        foreach ($iterator as $line) {
            if (!$this->isPriceLine($line)) {
                $tempFlight[self::FLIGHTS_KEY][] =$this->clearArray( $line);
            } else {
                $tempFlight[self::PRICE_KEY] = $this->clearArray($line);

                list($externalId) = $line;
                $flights[$externalId] = $tempFlight;

                if(count($flights) % self::BATCH_SIZE == 0) {
                    $this->flightCreator->saveBatch($flights);
                    unset($flights);
                }
                unset($tempFlight);
            }
        }
    }

    private function iterateCsv()
    {
        $count = 0;
        while(!$this->file->eof()) {
            yield $this->file->fgetcsv();
            $count++;
        }

        return $count;
    }

    private function clearArray(array $line): array
    {
        return array_filter($line);
    }

    private function isPriceLine(array $line): bool
    {
        return isset($line[13]) && $line[13] !== "";
    }
}