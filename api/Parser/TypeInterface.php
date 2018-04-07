<?php
/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace Api\Parser;


use Api\Report\FileReporterInterface;

interface TypeInterface
{
    /**
     * @return FileReporterInterface
     */
    public function getReport(): FileReporterInterface;

    /**
     * @param string $html
     */
    public function execute(string $currentUrl, string $html): void;
}