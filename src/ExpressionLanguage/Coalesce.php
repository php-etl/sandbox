<?php

declare(strict_types=1);

namespace Howto\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;

final class Coalesce extends ExpressionFunction
{
    public function __construct($name)
    {
        parent::__construct(
            $name,
            \Closure::fromCallable([$this, 'compile'])->bindTo($this),
            \Closure::fromCallable([$this, 'evaluate'])->bindTo($this)
        );
    }

    private function compile(string ...$values)
    {
        return implode(' ?? ', [...$values, 'null']);
    }

    private function evaluate(array $context, ...$values)
    {
        foreach ($values as $value) {
            if (null !== $value) {
                return $value;
            }
        }

        return null;
    }
}
