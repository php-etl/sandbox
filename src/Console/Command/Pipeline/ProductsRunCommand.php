<?php

declare(strict_types=1);

namespace Howto\Console\Command\Pipeline;

use Compiled\ProductSpaghettiMapper;
use Howto\Flow\CallableTransformer;
use Howto\Flow\GenericCSVExtractor;
use Howto\Flow\LDJSONLoader;
use Kiboko\Component\ETL\Pipeline;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ProductsRunCommand extends Command
{
    public static $defaultName = 'pipeline:products:run';

    protected function configure()
    {
        $this->setDescription('Read and process products data');

        $this->addArgument('source', InputArgument::REQUIRED);
        $this->addArgument('sink', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $source = $input->getArgument('source');
        $sink = $input->getArgument('sink');

        $pipeline = new Pipeline\Pipeline(new Pipeline\PipelineRunner());

        $pipeline
            ->extract(
                new GenericCSVExtractor(new \SplFileObject($source, 'rb'), [
                    'sku', 'name', 'slug', 'shortDescription', 'description', 'price', 'image_1-url', 'image_1-code', 'image_1-name', 'image_1-slug',
                    'image_2-url', 'image_2-code', 'image_2-name', 'image_2-slug', 'image_3-url', 'image_3-code', 'image_3-name', 'image_3-slug', 'image_4-url',
                    'image_4-code', 'image_4-name', 'image_4-slug',
                    ])
            )
            ->transform(
                new CallableTransformer(new ProductSpaghettiMapper())
            )
            ->load(
                new LDJSONLoader(new \SplFileObject($sink, 'wb'))
            );

        $pipeline->run();

        return 0;
    }
}