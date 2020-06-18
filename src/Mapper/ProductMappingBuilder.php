<?php declare(strict_types=1);

namespace Howto\Mapper;

use Kiboko\Component\ETL\Config\ArrayBuilder;
use Kiboko\Component\ETL\Config\MapperBuilderInterface;
use Kiboko\Component\ETL\FastMap\Contracts\MapperInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ProductMappingBuilder implements MapperBuilderInterface
{
    /** @var ExpressionLanguage */
    private $expressionLanguage;

    public function __construct(ExpressionLanguage $expressionLanguage)
    {
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
            ->copy('[sku]', '[sku]')
            ->copy('[name]', '[name]')
            ->expression('[slug]', 'coalesce( input["slug"], slugify( input["name"] ) )')
            ->expression('[shortDescription]', 'coalesce( input["shortDescription"] )')
            ->expression('[description]', 'coalesce( input["description"], input["shortDescription"] )')
            ->expression('[price]', 'input["price"]')
            ->end();

        return $builder->getMapper();
    }
}