@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <avatar-form :user="{{ $profileUser }}"></avatar-form>
{{--            {{ $threads->links() }}--}}
        </div>

        @forelse( $activities as $date => $activity )
            <h3 class="page-header">{{ $date }}</h3>
            @foreach($activity as $record)
                @if( view()->exists("profiles.activities.{$record->type}"))
                    @include ("profiles.activities.{$record->type}", ['activity' => $record])
                @endif
            @endforeach
        @empty
            <p>There is no activity for this user yet.</p>
        @endforelse
    </div>
@endsection