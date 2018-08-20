<?php

/*
 * This file is part of the iidestiny/weather.
 *
 * (c) 罗彦 <iidestiny@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace IiDestiny\Weather;

use GuzzleHttp\Client;
use IiDestiny\Weather\Exceptions\HttpException;
use IiDestiny\Weather\Exceptions\InvalidArgumentException;

class Weather
{
    protected $ak;

    protected $sn;

    protected $guzzleOptions = [];

    /**
     * Weather constructor.
     *
     * @param $ak
     * @param $sn
     */
    public function __construct($ak, $sn = null)
    {
        $this->ak = $ak;
        $this->sn = $sn;
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $optinns)
    {
        $this->guzzleOptions = $optinns;
    }

    public function getWeather($location, $format = 'json', $coordType = null)
    {
        $url = 'http://api.map.baidu.com/telematics/v3/weather';

        if (!in_array($format, ['xml', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: '.$format);
        }

        $query = array_filter([
            'ak' => $this->ak,
            'sn' => $this->sn,
            'location' => $location,
            'output' => $format,
            'coord_type' => $coordType,
        ]);

        try {
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();

            return 'json' === $format ? \json_decode($response, true) : $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
