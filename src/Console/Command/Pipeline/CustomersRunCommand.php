<?php

declare(strict_types=1);

namespace Howto\Console\Command\Pipeline;

use Howto\Flow\GenericCSVExtractor;
use Howto\Flow\LDJSONLoader;
use Kiboko\Component\ETL\Pipeline;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CustomersRunCommand extends Command
{
    public static $defaultName = 'pipeline:customers:run';


    protected function configure()
    {
        $this->setDescription('Read and process customers data');

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
                new GenericCSVExtractor(new \SplFileObject($source, 'rb'), ['firstName', 'lastName', 'email', 'address', 'postcode', 'city'])
            )
            ->load(
                new LDJSONLoader(new \SplFileObject($sink, 'wb'))
            );

        $pipeline->run();

        return 0;
    }
}