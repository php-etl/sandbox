<?php

declare(strict_types=1);

namespace Howto\Flow;

final class PDOExtractor
{
    /** var \PDOStatement */
    private $preparedStatement;
    /** @var array */
    private $arguments;

    public function __construct(\PDOStatement $preparedStatement, array $arguments = [])
    {
        $this->preparedStatement = $preparedStatement;
        $this->arguments = $arguments;
    }

    public function extract(): iterable
    {
        $this->preparedStatement->execute($this->arguments);

        $this->preparedStatement->setFetchMode(\PDO::FETCH_ASSOC);
        foreach ($this->preparedStatement as $line) {
            yield $line;
        }
    }
}