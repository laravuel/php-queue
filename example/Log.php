<?php

namespace App;

class Log
{
    public $name = '';

    public function __construct($name = '')
    {
        $this->name = $name;
    }

    /**
     * 必须实现handle方法，用来处理消息
     */
    public function handle()
    {
        file_put_contents(__DIR__ . '/ouput.log', $this->name . '_' . date('Y-m-d H:i:s') . "\r\n", FILE_APPEND);
    }
}
