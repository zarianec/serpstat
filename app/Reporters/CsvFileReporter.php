<?php
declare(strict_types=1);

/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace App\Reporters;


use Api\Report\FileReporterInterface;

class CsvFileReporter implements FileReporterInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $prefix = '';

    /**
     * @var string
     */
    private $filename;

    /**
     * CsvReporter constructor.
     * @param string $filename
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @inheritdoc
     */
    public function setName($name): void
    {
        $this->filename = $name;
    }

    /**
     * @inheritdoc
     */
    public function setPrefix($prefix): void
    {
        $this->prefix = $prefix;
    }

    /**
     * @inheritdoc
     */
    public function addRow(array $data): void
    {
        $file = fopen($this->path . DIRECTORY_SEPARATOR . $this->getFileName(), 'a');

        fputcsv($file, $data);

        fclose($file);
    }

    /**
     * @inheritdoc
     */
    public function getFileName(): string
    {
        return ($this->prefix ? $this->prefix . '_' : '') . $this->filename . '.csv';
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return BASE_URL . '/reports/' . $this->getFileName();
    }

    /**
     * Reset previous file for current type
     */
    public function reset(): void
    {
        $file = $this->path . DIRECTORY_SEPARATOR . $this->getFileName();
        if (is_file($file)) {
            unlink($file);
        }
    }
}