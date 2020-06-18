<?php

declare(strict_types=1);

namespace Howto\Flow;

use Kiboko\Component\ETL\Bucket\AcceptanceResultBucket;
use Kiboko\Component\ETL\Contracts\LoaderInterface;

final class LDJSONLoader implements LoaderInterface
{
    /**
     * @var \SplFileObject
     */
    private $file;

    /**
     * @param \SplFileObject $file
     */
    public function __construct(\SplFileObject $file)
    {
        $this->file = $file;
    }

    public function load(): \Generator
    {
        while (true) {
            $line = yield;

            $this->file->fwrite(json_encode($line) . "\n");

            yield new AcceptanceResultBucket($line);
        }
    }
}