<?php
namespace App;


use Illuminate\Support\Facades\Redis;

class Trending
{
    public function get()
    {
        return array_map('json_decode', Redis::zrevrange($this->getKey(), 0, 4));
    }

    public function push(Thread $thread)
    {
        Redis::zincrby($this->getKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
    }

    public function reset()
    {
        Redis::del($this->getKey());
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return app()->environment('testing') ? 'testing_trending_threads' : 'trending_threads';
    }
}