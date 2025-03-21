@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}

                    <!-- Add the button below -->
                    <a href="{{ url('/') }}" class="btn btn-primary mt-3">{{ __('Go to Home') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection