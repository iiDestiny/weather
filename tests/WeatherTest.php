<?php

/*
 * This file is part of the iidestiny/weather.
 *
 * (c) 罗彦 <iidestiny@vip.qq.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace IiDestiny\Weather\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use IiDestiny\Weather\Exceptions\HttpException;
use IiDestiny\Weather\Exceptions\InvalidArgumentException;
use IiDestiny\Weather\Weather;
use Mockery\Matcher\AnyArgs;
use PHPUnit\Framework\TestCase;

class WeatherTest extends TestCase
{
    public function testSetGuzzleOptions()
    {
        $w = new Weather('mock-ak');

        // 设置参数前，timeout 为 null
        $this->assertNull($w->getHttpClient()->getConfig('timeout'));

        // 设置参数
        $w->setGuzzleOptions(['timeout' => 5000]);

        // 设置参数后，timeout 为 5000
        $this->assertSame(5000, $w->getHttpClient()->getConfig('timeout'));
    }

    public function testGetWeatherWithGuzzleRuntimeException()
    {
        $client = \Mockery::mock(Client::class);
        $client->allows()
            ->get(new AnyArgs())// 由于上面的用例已经验证过参数传递，所以这里就不关心参数了。
            ->andThrow(new \Exception('request timeout')); // 当调用 get 方法时会抛出异常。

        $w = \Mockery::mock(Weather::class, ['mock-ak'])->makePartial();
        $w->allows()->getHttpClient()->andReturn($client);

        // 接着需要断言调用时会产生异常。
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('request timeout');

        $w->getWeather('深圳');
    }

    public function testGetWeather()
    {
        // 创建模拟接口响应值
        /*$response = new Response(200, [], '{"success": true}');
        // 模拟 http
        $client = \Mockery::mock(Client::class);
        // 指定将会产生的形为（在后续的测试中将会按下面的参数来调用）。
        $client->shouldReceive('get')
            ->with('http://api.map.baidu.com/telematics/v3/weather', [
                'query' => [
                    'ak'       => 'mock-ak',
                    'location' => '深圳',
                    'output'   => 'json',
                ],
            ])
            ->andReturn($response);
        // 将 `getHttpClient` 方法替换为上面创建的 http client 为返回值的模拟方法。
        $w = \Mockery::mock(Weather::class, ['mock-ak'])->makePartial();
        // $client 为上面创建的模拟实例。
        $w->shouldReceive('getHttpClient')
            ->andReturn($client);

        $this->assertSame(['success' => true], $w->getWeather('深圳'));*/

        // xml
        /* $response = new Response(200, [], '<hello>content</hello>');
         $client = \Mockery::mock(Client::class);
         $client->allows()->get('http://api.map.baidu.com/telematics/v3/weather', [
             'query' => [
                 'ak'       => 'mock-ak',
                 'location' => '深圳',
                 'output'   => 'xml',
             ],
         ])->andReturn($response);

         $w = \Mockery::mock(Weather::class, ['mock-ak'])->makePartial();
         $w->allows()->getHttpClient()->andReturn($client);

         $this->assertSame('<hello>content</hello>', $w->getWeather('深圳', 'xml'));*/
    }

    public function testGetHttpClient()
    {
        $w = new Weather('mock-ak');

        // 断言返回结果为 GuzzleHttp\ClientInterface 实例
        $this->assertInstanceOf(ClientInterface::class, $w->getHttpClient());
    }

    /**
     * @throws InvalidArgumentException
     * @throws \IiDestiny\Weather\Exceptions\HttpException
     */
    public function testGetWeatherWithInvalidFormat()
    {
        $w = new Weather('mock-ak');

        // 断言会抛出此异常类
        $this->expectException(InvalidArgumentException::class);

        // 断言异常消息为 'Invalid response format: array'
        $this->expectExceptionMessage('Invalid response format: array');

        // 因为支持的格式为 xml/json，所以传入 array 会抛出异常
        $w->getWeather('深圳', 'array');

        // 如果没有抛出异常，就会运行到这行，标记当前测试没成功
        $this->fail('Faild to asset getWeather throw exception with invalid argument.');
    }
}
