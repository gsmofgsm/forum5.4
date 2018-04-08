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
        Activity::create([
            'type' => $this->getActivityType($event),
            'user_id' => $this->user_id,
            'subject_id' => $this->id,
            'subject_type' => get_class($this)
        ]);
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