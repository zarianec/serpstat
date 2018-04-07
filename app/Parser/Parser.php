<?php
declare(strict_types=1);

/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace App\Parser;


use Api\Parser\TypeInterface;
use App\Exceptions\Parser\BadUrlException;
use App\Helpers\HttpClient;
use App\Helpers\StringHelper;

class Parser
{
    private $parsedLinks = [];

    /**
     * @var TypeInterface[]
     */
    private $types = [];

    /**
     * @var HttpClient
     */
    private $http;

    /**
     * @var StringHelper
     */
    private $stringHelper;

    /**
     * Parser constructor.
     * @param HttpClient $http
     * @param StringHelper $stringHelper
     */
    public function __construct(HttpClient $http, StringHelper $stringHelper)
    {
        $this->http = $http;
        $this->stringHelper = $stringHelper;
    }

    /**
     * @param TypeInterface $type
     */
    public function addType(TypeInterface $type): void
    {
        array_push($this->types, $type);
    }

    /**
     * @param string $url
     * @throws \Exception
     */
    public function run(string $url)
    {
        print "Parsing url: $url" . PHP_EOL;

        $domain = $this->stringHelper->extractDomain($url);

        if (!$domain) {
            throw new BadUrlException("You provided bad url for parsing");
        }

        $html = $this->http->getContent($url);

        if (!$html) {
            return;
        }

        foreach ($this->types as $type) {
            $type->execute($url, $html);
        }

        $internalLinks = $this->getInternalLinks($html, $domain);

        foreach ($internalLinks as $link) {
            $link = ltrim($link, '//');
            if (in_array($link, $this->parsedLinks)) {
                continue;
            }

            $this->parsedLinks[] = $link;

            $this->run($link);
        }
    }

    /**
     * @return array
     */
    public function getReports()
    {
        $reports = [];

        foreach ($this->types as $type) {
            $reports[get_class($type)] = $type->getReport();
        }

        return $reports;
    }

    /**
     * @param string $html
     * @param string $domain
     * @return array
     */
    private function getInternalLinks(string $html, string $domain): array
    {
        preg_match_all('/<a href="\K[^"]*(?:https?:\/\/|\/\/)' . preg_quote($domain) . '[^"]*/', $html, $matches);

        return $matches[0];
    }
}