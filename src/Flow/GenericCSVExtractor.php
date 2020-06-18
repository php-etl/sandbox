<?php declare(strict_types=1);

namespace Howto\Flow;

use Kiboko\Component\ETL\Batch\Item\FileInvalidItem;
use Kiboko\Component\ETL\Batch\Item\InvalidItemException;
use Kiboko\Component\ETL\Contracts\ExtractorInterface;

final class GenericCSVExtractor implements ExtractorInterface
{
    /** @var \SplFileObject */
    private $file;
    /** @var array */
    private $columns;
    /** @var string */
    private $delimiter;
    /** @var string */
    private $enclosure;
    /** @var string */
    private $escape;

    public function __construct(
        \SplFileObject $file,
        array $columns,
        string $delimiter = ',',
        string $enclosure = '"',
        string $escape = '\\'
    ) {
        $this->file = $file;
        $this->columns = $columns;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    public function extract(): iterable
    {
        $this->cleanBom();

        if ($this->file->eof()) {
            return;
        }

        $columnsCount = count($this->columns);

        $lineNumber = 0;
        while (!$this->file->eof()) {
            $line = $this->file->fgetcsv($this->delimiter, $this->enclosure, $this->escape);
            $lineColumnsCount = count($line);
            ++$lineNumber;

            if ($lineColumnsCount !== $columnsCount) {
                if ($lineColumnsCount <= 1 && $line[0] === null && $this->file->eof()) {
                    // Ignore if there is an empty line at end of file
                    continue;
                }

                throw new InvalidItemException(
                    'The line %line% does not contain the same amount of columns than the first line.',
                    new FileInvalidItem($line, $lineNumber),
                    [
                        '%line%' => $lineNumber
                    ]
                );
            }

            yield array_combine($this->columns, $line);
        }
    }

    public function cleanBom()
    {
        $bom = $this->file-> fread(3);
        if (!preg_match('/^\\xEF\\xBB\\xBF$/', $bom)) {
            $this->file-> seek(0);
        }
    }
}