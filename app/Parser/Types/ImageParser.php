<?php
declare(strict_types=1);

/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace App\Parser\Types;


use Api\Parser\TypeInterface;
use Api\Report\FileReporterInterface;
use App\Helpers\StringHelper;

class ImageParser implements TypeInterface
{
    const IMAGES_PATH = ROOT_PATH . '/var/images';

    /**
     * @var FileReporterInterface
     */
    private $reporter;

    /**
     * @var StringHelper
     */
    private $stringHelper;

    /**
     * @var array
     */
    private $parsedImages = [];

    /**
     * ImageParser constructor.
     * @param FileReporterInterface $reporter
     * @param StringHelper $stringHelper
     */
    public function __construct(FileReporterInterface $reporter, StringHelper $stringHelper)
    {
        $this->reporter = $reporter;

        $this->reporter->setName('images');
        $this->reporter->reset();
        $this->stringHelper = $stringHelper;
    }

    /**
     * @inheritdoc
     */
    public function getReport(): FileReporterInterface
    {
        return $this->reporter;
    }

    /**
     * @inheritdoc
     */
    public function execute(string $currentPage, string $html): void
    {
        $domain = $this->stringHelper->extractDomain($currentPage);

        preg_match_all("/\<img.+src\=(?:\"|\')(.+?)(?:\"|\')(?:.+?)\>/", $html, $matches);

        foreach ($matches[1] as $image) {
            $imageUrl = $this->normalizeUrl($image, $domain);

            if (in_array($imageUrl, $this->parsedImages)) {
                continue;
            }

            $this->parsedImages[] = $imageUrl;

            $fileInfo = pathinfo($imageUrl);
            $originalName = $fileInfo['filename'] . '.' . $fileInfo['extension'];

            echo "-- $originalName" . PHP_EOL;

            $file = $this->downloadImage($imageUrl, $domain);
            $localUrl = BASE_URL . "/images/$domain/$file";
            if ($file !== false) {
                $this->reporter->addRow([$originalName, $imageUrl, $file, $localUrl, $currentPage]);
            }
        }
    }

    /**
     * @param $url
     * @return string
     */
    private function downloadImage($url, $domain)
    {
        $info = pathinfo($url);
        $siteDir = self::IMAGES_PATH . "/$domain";
        $path = $siteDir . '/' . $info['filename'] . '.' . $info['extension'];

        if (!is_dir($siteDir)) {
            mkdir($siteDir);
        }

        $ch = curl_init($url);
        $fp = fopen($path, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        fclose($fp);

        if ($status != 200) {
            unlink($path);
            return false;
        }

        $hash = sha1_file($path);
        $newPath = $siteDir . '/' . $hash . '.' . $info['extension'];

        rename($path, $newPath);

        return $hash . '.' . $info['extension'];
    }

    /**
     * @param $url
     * @param $domain
     * @return string
     */
    private function normalizeUrl($url, $domain)
    {
        $pos = strpos($url, $domain);

        if (false !== $pos) {
            $url = substr($url, $pos + strlen($domain));
        }

        return $domain . '/' . ltrim($url, '/');
    }
}