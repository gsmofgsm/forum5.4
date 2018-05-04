<?php

namespace App;

use Illuminate\Support\Facades\Redis;

trait RecordVisits
{

    public function recordVisit()
    {
        Redis::incr($this->visitsKey());
        return $this;
    }

    public function visits()
    {
        return Redis::get($this->visitsKey()) ?? 0;
    }

    public function resetVisits()
    {
        Redis::del($this->visitsKey());
    }

    /**
     * @return string
     */
    protected function visitsKey(): string
    {
        return "threads.{$this->id}.visits";
    }
}