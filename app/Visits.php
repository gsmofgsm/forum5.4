<?php

namespace App;


use Illuminate\Support\Facades\Redis;

class Visits
{
    /**
     * @var Thread
     */
    protected $thread;

    /**
     * Visits constructor.
     */
    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    public function reset()
    {
        Redis::del($this->getKey());
        return $this;
    }

    public function count()
    {
        return Redis::get($this->getKey()) ?? 0;
    }

    public function record()
    {
        Redis::incr($this->getKey());
        return $this;
    }

    protected function getKey(): string
    {
        return "threads.{$this->thread->id}.visits";
    }
}