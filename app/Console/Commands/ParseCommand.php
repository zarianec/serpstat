<?php
declare(strict_types=1);

/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace App\Console\Commands;


use App\Helpers\HttpClient;
use App\Helpers\StringHelper;
use App\Parser\Parser;
use App\Parser\Types\ImageParser;
use App\Reporters\CsvFileReporter;

class ParseCommand extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    public function getDefinitions(): array
    {
        return ['--url'];
    }

    /**
     * @inheritdoc
     */
    public function run(): void
    {
        print "I am Parser! And i'm going to parse {$this->getArgument('url')}!" . PHP_EOL;

        // TODO: Add DI container
        $httpClient = new HttpClient();
        $stringHelper = new StringHelper();
        $reporter = new CsvFileReporter(ROOT_PATH . '/var/reports');

        $domain = $stringHelper->extractDomain((string)$this->getArgument('url'));
        $reporter->setPrefix($domain);

        $parser = new Parser($httpClient, $stringHelper);
        $parser->addType(new ImageParser($reporter, $stringHelper));

        $parser->run((string)$this->getArgument('url'));
        $reports = $parser->getReports();

        foreach ($reports as $type => $report) {
            print "Report for $type: {$report->getLink()}" . PHP_EOL;
        }

        print "I am done!" . PHP_EOL;
        print PHP_EOL;
    }
}