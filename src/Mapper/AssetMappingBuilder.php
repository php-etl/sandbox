<?php declare(strict_types=1);

namespace Howto\Mapper;

use Kiboko\Component\ETL\Config\ArrayBuilder;
use Kiboko\Component\ETL\Config\MapperBuilderInterface;
use Kiboko\Component\ETL\FastMap\Contracts\MapperInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class AssetMappingBuilder implements MapperBuilderInterface
{
    /** @var int int */
    private $index;
    /** @var ExpressionLanguage */
    private $expressionLanguage;

    public function __construct(int $index, ExpressionLanguage $expressionLanguage)
    {
        $this->index = $index;
        $this->expressionLanguage = $expressionLanguage;
    }

    public function end(): ?MapperBuilderInterface
    {
        return null;
    }

    public function getMapper(): MapperInterface
    {
        $builder = new ArrayBuilder(null, $this->expressionLanguage);

        $builder->children()
            ->copy(
                sprintf('[images][%1$d][url]', $this->index),
                sprintf('[image_%1$d-url]', $this->index)
            )
            ->copy(
                sprintf('[images][%1$d][code]', $this->index),
                sprintf('[image_%1$d-code]', $this->index)
            )
            ->copy(
                sprintf('[images][%1$d][name]', $this->index),
                sprintf('[image_%1$d-name]', $this->index)
            )
            ->expression(
                sprintf('[images][%1$d][slug]', $this->index),
                sprintf('coalesce( input["image_%1$d-slug"], slugify( input["image_%1$d-name"] ) )', $this->index)
            )
            ->end();

        return $builder->getMapper();
    }
}