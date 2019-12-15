# laravuel/php-queue
php基于redis实现一个简单的消息队列

## 安装
1. 安装[phpredis](https://github.com/phpredis/phpredis)扩展

2. 通过composer安装`laravuel/php-queue`
```
composer require laravuel/php-queue
```

## 使用
1. 客户端
```php
namespace App;

require __DIR__ . '/../vendor/autoload.php';

use App\Log;
use Carbon\Carbon;
use Laravuel\PhpQueue\Queue;

// 加载配置
$config = [
    'host' => '127.0.0.1',  // redis 地址
    'port' => '6379',   // redis 端口
    'password' => '',   // redis 密码
    'timezone' => 'Asia/Shanghai',  // 时区
    'key' => 'laravuel-php-queue',  // redis key
];
// 实例化队列对象
$queue = new Queue($config);

// 插入队列 立即执行
// Log类参考 example文件夹下的Log.php
$queue->push(new Log('test'));


// 延时执行 10秒后
// 10秒后会自动执行 Log::handle方法
$queue->push(new Log('test2'), Carbon::now()->addSeconds(10));
```
2. 服务器

首先在项目目录下创建一个run.php文件，以下参考：
```php
require __DIR__ . '/../vendor/autoload.php';

use Laravuel\PhpQueue\Queue;

$queue = new Queue([
    'host' => '127.0.0.1',  // redis 地址
    'port' => '6379',   // redis 端口
    'password' => '',   // redis 密码
    'timezone' => 'Asia/Shanghai',  // 时区
    'key' => 'laravuel-php-queue',  // redis key
]);

// 开启监听
$queue->listen();

```
在命令行执行（推荐使用Supervisor，避免进程由于某些原因挂掉）：
```
php /xxx/xxx/run.php
```