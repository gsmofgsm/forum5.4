@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Forum Threads</div>

                    <div class="card-body">
                        @include('threads._list')

                        {{ $threads->render() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
