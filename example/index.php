<?php

/**
 * 客户端
 */

namespace App;

require __DIR__ . '/../vendor/autoload.php';

use App\Log;
use Carbon\Carbon;
use Laravuel\PhpQueue\Queue;

// 加载配置
$config = require('./config.php');
// 实例化队列对象
$queue = new Queue($config);

// 插入队列 立即执行
// $queue->push(new Log('test'));


// 延时执行 10秒后
// 10秒后会自动执行 Log::handle方法
$queue->push(new Log('test2'), Carbon::now()->addSeconds(10));
