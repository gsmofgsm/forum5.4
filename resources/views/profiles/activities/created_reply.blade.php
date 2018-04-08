<div class="card">
    <div class="card-header">
        <div class="level">
            <span class="flex">
                {{ $profileUser->name }} replied to
                <a href="{{ $activity->subject->thread->path() }}">
                    "{{ $activity->subject->thread->title }}"
                </a>
            </span>
            {{--                            <span>{{ $thread->created_at->diffForHumans() }}</span>--}}
        </div>
    </div>

    <div class="card-body">
        {{ $activity->subject->body }}
    </div>
</div>