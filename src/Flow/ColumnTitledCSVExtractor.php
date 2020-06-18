<?php declare(strict_types=1);

namespace Howto\Flow;

use Kiboko\Component\ETL\Batch\Item\FileInvalidItem;
use Kiboko\Component\ETL\Batch\Item\InvalidItemException;
use Kiboko\Component\ETL\Contracts\ExtractorInterface;

final class CSVExtractor implements ExtractorInterface
{
    /** @var \SplFileObject */
    private $file;
    /** @var string */
    private $delimiter;
    /** @var string */
    private $enclosure;
    /** @var string */
    private $escape;

    public function __construct(
        \SplFileObject $file,
        string $delimiter = ',',
        string $enclosure = '"',
        string $escape = '\\'
    ) {
        $this->file = $file;
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

        $columns = $this->file->fgetcsv($this->delimiter, $this->enclosure, $this->escape);

        $columnsCount = count($columns);

        $lineNumber = 0;
        while (!$this->file->eof()) {
            $line = $this->file->fgetcsv($this->delimiter, $this->enclosure, $this->escape);
            $lineColumnsCount = count($line);
            ++$lineNumber;

            if ($lineColumnsCount !== $columnsCount) {
                if ($lineColumnsCount <= 0 && $this->file->eof()) {
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

            yield array_combine($columns, $line);
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