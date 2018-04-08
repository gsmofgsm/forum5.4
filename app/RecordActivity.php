<?php
/**
 * Created by PhpStorm.
 * User: gsm
 * Date: 2018/4/8
 * Time: 15:05
 */

namespace App;


trait RecordActivity
{
    protected static function bootRecordActivity()
    {
        static::created(function ($thread) {
            $thread->recordActivity('created');
        });
    }

    protected function recordActivity($event)
    {
        $this->activity()->create([
            'type' => $this->getActivityType($event),
            'user_id' => $this->user_id
        ]);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }

    /**
     * @param $event
     * @return string
     */
    protected function getActivityType($event): string
    {
        return $event . '_' . strtolower(class_basename($this));
    }
}