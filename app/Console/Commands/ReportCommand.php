<?php
declare(strict_types=1);

/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace App\Console\Commands;


use App\Helpers\StringHelper;

class ReportCommand extends AbstractCommand
{
    /**
     * @var bool
     */
    private $hasReports = false;

    /**
     * @inheritdoc
     */
    public function getDefinitions(): array
    {
        return ['--domain'];
    }

    /**
     * @inheritdoc
     */
    public function run(): void
    {
        $stringHelper = new StringHelper();
        $domain = $stringHelper->extractDomain((string)$this->getArgument('domain'));

        $this->outputReportForType('images', $domain);

        if (!$this->hasReports) {
            print "No reports for this domain" . PHP_EOL;
        }
    }

    /**
     * @param $type
     * @param $domain
     */
    private function outputReportForType($type, $domain)
    {
        $reportsPath = ROOT_PATH . '/var/reports';
        $imagesReport = "{$domain}_{$type}.csv";

        if (is_file($reportsPath . '/' . $imagesReport)) {
            $this->hasReports = true;
            print "Images report for $domain: /var/reports/" . $imagesReport . PHP_EOL;
        }
    }
}