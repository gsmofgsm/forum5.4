@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header">
            <h1>
                {{ $profileUser->name }}
                <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
            </h1>

            @foreach( $activities as $date => $activity )
                <h3 class="page-header">{{ $date }}</h3>
                @foreach($activity as $record)
                    @include ("profiles.activities.{$record->type}", ['activity' => $record])
                @endforeach

            @endforeach

{{--            {{ $threads->links() }}--}}
        </div>
    </div>
@endsection