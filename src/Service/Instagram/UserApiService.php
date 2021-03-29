<?php

/**
 * This file is part of the @modelsua-api package.
 */

namespace App\Service\Instagram;


use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProfileApiService.
 */
class UserApiService
{
    public const URL_TEMPLATE = 'https://www.instagram.com/%s/?__a=1';
    public const HTTP_HEADERS = [
        'authority'                 => 'www.instagram.com',
        'pragma'                    => 'no-cache',
        'cache-control'             => 'no-cache',
        'upgrade-insecure-requests' => '1',
        'user-agent'                => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36',
        'accept'                    => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
        'sec-fetch-site'            => 'none',
        'sec-fetch-mode'            => 'navigate',
        'sec-fetch-dest'            => 'document',
        'accept-language'           => 'uk-UA,uk;q=0.9,en-US;q=0.8,en;q=0.7,ru;q=0.6',
        'cookie'                    => 'ig_did=28E99E5C-F677-4CC0-B869-E24070F605D6; mid=XeaydgAEAAFMXTIgIhtk_gbi8xKG; fbm_124024574287414=base_domain=.instagram.com; datr=tPNgXizTFyHz1MAqCGeBE006; csrftoken=mScL4U3LRZXOAOvyxpVvXuil24FEzy2P; ds_user_id=31492977128; sessionid=31492977128%3AoxqwCeJaGkGunY%3A2; shbid=15040; shbts=1589352621.0210261; urlgen="{\"178.150.38.186\": 13188}:1jYnVj:LOMkx7kxp1EUduQLXoVZmjz1oVc"',
    ];

    /**
     * @var CurlHttpClient
     */
    private CurlHttpClient $client;

    public function __construct()
    {
        $this->client = new CurlHttpClient();
    }

    public function getProfileData(string $username)
    {
        $url     = $this->buildUrl($username);
        $content = $this->sendRequest($url, Request::METHOD_GET, self::HTTP_HEADERS);

        file_put_contents(sprintf("%s/../../../var/cache/%s.log", __DIR__, $username), $content);

        return $this->parseJson($content);
    }

    private function buildUrl(string $username)
    {
        return sprintf(self::URL_TEMPLATE, $username);
    }

    private function sendRequest(string $url, string $method = Request::METHOD_GET, array $headers = [])
    {
        return $this->client
            ->request($method, $url, ['headers' => $headers])
            ->getContent();
    }

    private function parseJson(string $content)
    {
        return json_decode($content, true, 512, JSON_THROW_ON_ERROR);
    }
}