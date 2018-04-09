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
        foreach(static::getActivitiesToRecord() as $event ) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        static::deleting(function($model){
            $model->activity()->delete();
        });
    }

    protected static function getActivitiesToRecord()
    {
        return ['created'];
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