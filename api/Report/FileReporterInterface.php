<?php
declare(strict_types=1);

/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace Api\Report;


interface FileReporterInterface
{
    /**
     * Set the name of the report
     * @param $name
     */
    public function setName(string $name): void;

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix): void;

    /**
     * @return string
     */
    public function getFilename(): string;

    /**
     * Return link to file
     *
     * @return string
     */
    public function getLink(): string;

    /**
     * @param array $data
     */
    public function addRow(array $data): void;
}