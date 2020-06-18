<?php

declare(strict_types=1);

namespace Howto\Console\Command\FastMap;

use Howto\ExpressionLanguage\Provider;
use Howto\Mapper\AssetMappingBuilder;
use Howto\Mapper\ProductMappingBuilder;
use Kiboko\Component\ETL\FastMap\Compiler;
use Kiboko\Component\ETL\FastMap\PropertyAccess\EmptyPropertyPath;
use Kiboko\Component\ETL\Metadata\ClassReferenceMetadata;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class CompileCommand extends Command
{
    public static $defaultName = 'fast-map:compile';

    protected function configure()
    {
        $this->setDescription('Compile FastMap mappers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $compiler = new Compiler\Compiler(new Compiler\Strategy\Spaghetti());

        $expressionLanguage = new ExpressionLanguage(null, [
            new Provider()
        ]);

        $compiler->compile(
            new Compiler\StandardCompilationContext(
                new EmptyPropertyPath(),
                __DIR__ . '/../../../../compiled/ProductSpaghettiMapper.php',
                new ClassReferenceMetadata('ProductSpaghettiMapper', 'Compiled')
            ),
            (new ProductMappingBuilder($expressionLanguage))->getMapper(),
            (new AssetMappingBuilder(1, $expressionLanguage))->getMapper(),
            (new AssetMappingBuilder(2, $expressionLanguage))->getMapper(),
            (new AssetMappingBuilder(3, $expressionLanguage))->getMapper(),
            (new AssetMappingBuilder(4, $expressionLanguage))->getMapper(),
        );

        return 0;
    }
}