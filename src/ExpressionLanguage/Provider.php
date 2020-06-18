<?php

declare(strict_types=1);

namespace Howto\ExpressionLanguage;

use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

final class Provider implements ExpressionFunctionProviderInterface
{
    public function getFunctions()
    {
        return [
            new Coalesce('coalesce'),
            new Slugify('slugify'),
        ];
    }
}
