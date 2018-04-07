<?php
declare(strict_types=1);

/**
 *
 * @author Artiom Stoianov <zarianec91@gmail.com>
 *
 */

namespace App\Helpers;


class HttpClient
{
    public function getContent($url)
    {
        $user_agent = 'Serpstat/Crawler 1.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POST => false,
            CURLOPT_USERAGENT => $user_agent,
            CURLOPT_COOKIEFILE => "cookie.txt",
            CURLOPT_COOKIEJAR => "cookie.txt",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CONNECTTIMEOUT => 120,
            CURLOPT_TIMEOUT => 120,
            CURLOPT_MAXREDIRS => 10,
        );

        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        $header = curl_getinfo($ch);
        curl_close($ch);

        return $content;
    }
}