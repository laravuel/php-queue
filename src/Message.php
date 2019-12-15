<?php

namespace Laravuel\PhpQueue;

use Carbon\Carbon;
use Exception;

class Message
{
    public $listener;
    public $delay = null;

    public function __construct($listener, $delay = null)
    {
        $this->listener = $listener;
        if ($delay) {
            $this->delay = $delay instanceof Carbon ? $delay : new Carbon($delay);
        }
    }

    public function trigger()
    {
        if (!$this->delay || Carbon::now()->gte($this->delay)) {
            $this->listener->handle();
            return true;
        }

        if (Carbon::now()->lt($this->delay)) {
            return false;
        }
    }
}
