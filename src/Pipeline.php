<?php declare(strict_types=1);

namespace Howto;

use Kiboko\Component\ETL\Contracts\ExtractingInterface;
use Kiboko\Component\ETL\Contracts\ExtractorInterface;
use Kiboko\Component\ETL\Contracts\LoaderInterface;
use Kiboko\Component\ETL\Contracts\LoadingInterface;
use Kiboko\Component\ETL\Contracts\PipelineInterface;
use Kiboko\Component\ETL\Contracts\TransformerInterface;
use Kiboko\Component\ETL\Contracts\TransformingInterface;

final class Pipeline implements PipelineInterface
{
    public function extract(ExtractorInterface $extractor): ExtractingInterface
    {
        // TODO: Implement extract() method.
    }

    public function load(LoaderInterface $loader): LoadingInterface
    {
        // TODO: Implement load() method.
    }

    public function feed(...$data): void
    {
        // TODO: Implement feed() method.
    }

    public function transform(TransformerInterface $transformer): TransformingInterface
    {
        // TODO: Implement transform() method.
    }
}