<?php

declare(strict_types=1);

namespace Howto\Flow;

use Kiboko\Component\ETL\Bucket\AcceptanceResultBucket;

final class PDOLoader
{
    /** @var \PDOStatement */
    private $preparedStatement;
    /** @var callable */
    private $fieldMapping;

    public function __construct(\PDOStatement $preparedStatement, callable $fieldMapping = null)
    {
        $this->preparedStatement = $preparedStatement;
        $this->fieldMapping = $fieldMapping ?? function($line) {
            return $line;
        };
    }

    public function load(): \Generator
    {
        while (true) {
            $line = yield;

            $this->preparedStatement->execute(($this->fieldMapping)($line));

            yield new AcceptanceResultBucket($line);
        }
    }
}