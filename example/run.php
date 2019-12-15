<?php

/**
 * 服务端 命令行模式启动 php /xxx/xxx/run.php
 */
require __DIR__ . '/../vendor/autoload.php';

use Laravuel\PhpQueue\Queue;

$config = require __DIR__ . '/config.php';

$queue = new Queue($config);

$queue->listen();
