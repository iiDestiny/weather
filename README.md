# weather

天气查询组件

## 安装

```shell
$ composer require iidestiny/weather -vvv
```

## 配置
env 加上配置信息

```
BAIDU_AK=xxxxx
BAIDU_SN=xxxxx
```

## 使用

```php
use IiDestiny\Weather\Weather;

$ak = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';

$weather = new Weather($ak);

// 返回数组格式
$response = $weather->getWeather('深圳');

// 批量获取
$response = $weather->getWeather('深圳|北京');

// 返回 XML 格式
$response = $weather->getWeather('深圳', 'xml');

// 按坐标获取
$response = $weather->getWeather('116.30,39.98', 'json');

// 批量坐标获取
$response = $weather->getWeather('116.43,40.75|120.22,43,33', 'json');

// 自定义坐标格式（coord_type）
$response = $weather->getWeather('116.306411,39.981839', 'json', 'bd09ll');
```

## 参数说明

```php
array | string   getWeather(string $location, string $format = 'json', string $coordType = null)
```

> $location 地点，中文或者坐标地址，多个用斗角逗号隔开
> $format 返回格式， json(默认)/xml, json 将会返回数组格式，xml 返回字符串格式。
> $coordType 坐标格式，允许的值为bd09ll、bd09mc、gcj02、wgs84，默认为 gcj02 经纬度坐标。
> 详情说明请参考官方：[http://lbsyun.baidu.com/index.php?title=car/api/weather](http://lbsyun.baidu.com/index.php?title=car/api/weather)

## License

MIT