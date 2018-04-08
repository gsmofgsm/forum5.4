<div class="card">
    <div class="card-header">
        <div class="level">
            <span class="flex">
                {{ $profileUser->name }} published
                <a href="{{ $activity->subject->path() }}">{{ $activity->subject->title }}</a>
            </span>
            {{--                            <span>{{ $thread->created_at->diffForHumans() }}</span>--}}
        </div>
    </div>

    <div class="card-body">
        {{ $activity->subject->body }}
    </div>
</div>