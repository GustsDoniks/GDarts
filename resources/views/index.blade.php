@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Button for New Game -->
                        <div class="mb-3">
                            <a href="{{ route('games.create') }}" class="btn btn-primary btn-block">New Game</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <!-- Button for New Tournament -->
                        <div class="mb-3">
                            <a href="{{ route('tournaments.create') }}" class="btn btn-primary btn-block">New Tournament</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
