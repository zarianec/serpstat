<?php
declare(strict_types=1);

/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace App\Helpers;


class StringHelper
{
    /**
     * @param string $url
     * @return mixed
     */
    public function extractDomain(string $url)
    {
        preg_match('/^(?:https?:\/\/)?(?:[^@\/\n]+@)?(?:www\.)?([^:\/\n]+)/', ltrim($url, '//'), $matches);

        if (empty($matches[1])) {
            return false;
        }

        return $matches[1];
    }
}