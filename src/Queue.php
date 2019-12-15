<?php

namespace Laravuel\PhpQueue;

class Queue
{
    public $redis;
    public $key = 'laravuel-php-queue';
    public $config = [];
    public $serializer;

    public function __construct($config)
    {
        date_default_timezone_set($config['timezone'] ?? "Asia/Shanghai");
        $this->config = $config;
        $this->connect();
        $this->key = $config['key'] ?? $this->key;
    }

    public function connect()
    {
        $this->redis = new \Redis();
        $this->redis->connect($this->config['host'], $this->config['port']);
        $this->redis->auth($this->config['password']);
    }

    /**
     * 添加一条消息
     */
    public function push($listener, $delay = null)
    {
        if (gettype($listener) != 'object') {
            throw new \Exception('linstener is not object');
        }
        if (!method_exists($listener, 'handle')) {
            throw new \Exception('linstener is not method: handle');
        }
        $this->redis->lpush($this->key, serialize(new Message($listener, $delay)));
    }

    /**
     * 删除并返回最新一条消息
     */
    public function pop()
    {
        return $this->redis->rpop($this->key);
    }

    /**
     * 监听消息队列
     */
    public function listen()
    {
        try {
            while (true) {
                if ($this->redis->exists($this->key) && $data = $this->pop()) {
                    if ($message = unserialize($data)) {
                        echo date('Y-m-d H:i:s') . "\r\n";
                        print_r($message);
                        echo "\r\n";
                        if (!$message->trigger()) {
                            $this->push($message->listener, $message->delay);
                        }
                    }
                }
                sleep(3);
            }
        } catch (\Exception $e) {
            $this->connect();
            $this->listen();
        }
    }
}
