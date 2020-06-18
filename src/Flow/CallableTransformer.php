<?php

declare(strict_types=1);

namespace Howto\Flow;

use Kiboko\Component\ETL\Bucket\AcceptanceResultBucket;
use Kiboko\Component\ETL\Contracts\TransformerInterface;

final class CallableTransformer implements TransformerInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function transform(): \Generator
    {
        while (true) {
            $line = yield;

            yield new AcceptanceResultBucket(($this->callback)($line));
        }
    }
}