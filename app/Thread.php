<?php

namespace App;

use App\Events\ThreadHasNewReply;
use App\Events\ThreadReceivedNewReply;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Thread extends Model
{
    use RecordActivity;

    protected $guarded = [];
    protected $with = ['creator', 'channel'];
    protected $appends = ['isSubscribed'];
    protected $casts = [
        'locked' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function($builder){
            $builder->withCount('replies');
        });

        static::deleting(function($thread){
            $thread->replies->each->delete();
        });

        static::created(function($thread){
            $thread->update(['slug' => $thread->title]);
        });
    }

    public function path()
    {
        return '/threads/' . $this->channel->slug . '/' . $this->slug;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

//    public function getReplyCountAttribute()
//    {
//        return $this->replies()->count();
//    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        event(new ThreadReceivedNewReply($reply));

        return $reply;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function getIsSubscribedAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    public function subscribe($userId=null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId=null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function hasUpdatesFor(User $user)
    {
        // Look in the cache for the proper key.
        $key = $user->readThreadCacheKey($this);

        // compare that carbon instance with the $thread->updated_at
        return $this->updated_at > cache($key);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        $slug = str_slug($value);

        $slug = "{$slug}-" . $this->id;

        $this->attributes['slug'] = $slug;
    }

    public function MarkBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }
}
