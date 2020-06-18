<?php

declare(strict_types=1);

namespace Howto\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

final class Slugify extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            \Closure::fromCallable([$this, 'compile'])->bindTo($this),
            \Closure::fromCallable([$this, 'evaluate'])->bindTo($this)
        );
    }

    private function compile(string $text)
    {
        return sprintf('preg_replace("/[^a-zA-Z0-9-]/", "-", %s)', $text);
    }

    private function evaluate(array $context, string $text)
    {
        return preg_replace('/[^a-zA-Z0-9-]/', '-', $text);
    }
}
