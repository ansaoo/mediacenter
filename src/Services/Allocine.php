<?php
/**
 * Created by IntelliJ IDEA.
 * User: ansaoo
 * Date: 08/01/19
 * Time: 09:22
 */

namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;

class Allocine
{
    private $_api_url = 'http://api.allocine.fr/rest/v3';
    private $_partner_key;
    private $_secret_key;
    private $client;

    public function __construct($partner_key, $secret_key)
    {
        $this->_partner_key = $partner_key;
        $this->_secret_key = $secret_key;
        $handler = new CurlHandler();
        $stack = HandlerStack::create($handler);
        $this->client = new Client([
            "base_uri" => $this->_api_url,
            "timeout" => 30,
            'handler' => $stack,
            'http_errors' => false
        ]);
    }

    /**
     * @param $method
     * @param $params
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function _do_request($method, $params)
    {
        $query_url = $this->_api_url.'/'.$method;
        // new algo to build the query
        $sed = date('Ymd');
        $sig = urlencode(base64_encode(sha1($this->_secret_key.http_build_query($params).'&sed='.$sed, true)));
        $query_url .= '?'.http_build_query($params).'&sed='.$sed.'&sig='.$sig;
        // do the request
        $params["sed"] = $sed;
        $params["sig"] = $sig;
        $response = $this->client->request('GET', $query_url, [
            "headers" => [
                "User-Agent" => $this->getRandomUserAgent()
            ]
        ]);
        return $response->getStatusCode() == 200 ? json_decode((string)$response->getBody()) : null;
    }

    private function getRandomUserAgent()
    {
        $v = rand(1, 4).'.'.rand(0, 9);
        $a = rand(0, 9);
        $b = rand(0, 99);
        $c = rand(0, 999);
        $userAgents = [
            "Mozilla/5.0 (Linux; U; Android $v; fr-fr; Nexus One Build/FRF91) AppleWebKit/5$b.$c (KHTML, like Gecko) Version/$a.$a Mobile Safari/5$b.$c",
            "Mozilla/5.0 (Linux; U; Android $v; fr-fr; Dell Streak Build/Donut AppleWebKit/5$b.$c+ (KHTML, like Gecko) Version/3.$a.2 Mobile Safari/ 5$b.$c.1",
            "Mozilla/5.0 (Linux; U; Android 4.$v; fr-fr; LG-L160L Build/IML74K) AppleWebkit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30",
            "Mozilla/5.0 (Linux; U; Android 4.$v; fr-fr; HTC Sensation Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30",
            "Mozilla/5.0 (Linux; U; Android $v; en-gb) AppleWebKit/999+ (KHTML, like Gecko) Safari/9$b.$a",
            "Mozilla/5.0 (Linux; U; Android $v.5; fr-fr; HTC_IncredibleS_S710e Build/GRJ$b) AppleWebKit/5$b.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/5$b.1",
            "Mozilla/5.0 (Linux; U; Android 2.$v; fr-fr; HTC Vision Build/GRI$b) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
            "Mozilla/5.0 (Linux; U; Android $v.4; fr-fr; HTC Desire Build/GRJ$b) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
            "Mozilla/5.0 (Linux; U; Android 2.$v; fr-fr; T-Mobile myTouch 3G Slide Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
            "Mozilla/5.0 (Linux; U; Android $v.3; fr-fr; HTC_Pyramid Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
            "Mozilla/5.0 (Linux; U; Android 2.$v; fr-fr; HTC_Pyramid Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari",
            "Mozilla/5.0 (Linux; U; Android 2.$v; fr-fr; HTC Pyramid Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/5$b.1",
            "Mozilla/5.0 (Linux; U; Android 2.$v; fr-fr; LG-LU3000 Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/5$b.1",
            "Mozilla/5.0 (Linux; U; Android 2.$v; fr-fr; HTC_DesireS_S510e Build/GRI$a) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/$c.1",
            "Mozilla/5.0 (Linux; U; Android 2.$v; fr-fr; HTC_DesireS_S510e Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile",
            "Mozilla/5.0 (Linux; U; Android $v.3; fr-fr; HTC Desire Build/GRI$a) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
            "Mozilla/5.0 (Linux; U; Android 2.$v; fr-fr; HTC Desire Build/FRF$a) AppleWebKit/533.1 (KHTML, like Gecko) Version/$a.0 Mobile Safari/533.1",
            "Mozilla/5.0 (Linux; U; Android $v; fr-lu; HTC Legend Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/$a.$a Mobile Safari/$c.$a",
            "Mozilla/5.0 (Linux; U; Android $v; fr-fr; HTC_DesireHD_A9191 Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1",
            "Mozilla/5.0 (Linux; U; Android $v.1; fr-fr; HTC_DesireZ_A7$c Build/FRG83D) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/$c.$a",
            "Mozilla/5.0 (Linux; U; Android $v.1; en-gb; HTC_DesireZ_A7272 Build/FRG83D) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/$c.1",
            "Mozilla/5.0 (Linux; U; Android $v; fr-fr; LG-P5$b Build/FRG83) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1"
        ];
        return $userAgents[rand(0, count($userAgents) - 1)];
    }

    /**
     * @param string $query
     * @param array $options
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function search(string $query, array $options = array())
    {
        // build the params
        $params = array(
            'partner' => $this->_partner_key,
            'q' => $query,
            'format' => $options['format'] ?? "json",
            'filter' => 'movie'
        );
        // do the request
        $response = $this->_do_request('search', $params);
        return $response;
    }

    /**
     * @param int $id
     * @param array $options
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get(int $id, array $options = array())
    {
        // build the params
        $params = array(
            'partner' => $this->_partner_key,
            'code' => $id,
            'profile' => 'large',
            'filter' => 'movie',
            'striptags' => 'synopsis,synopsisshort',
            'format' => $options['format'] ?? "json",
        );
        // do the request
        $response = $this->_do_request('movie', $params);
        return $response;
    }
}