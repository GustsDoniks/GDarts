@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('games.create') }}" class="btn btn-primary btn-block">New Game</a>
                        </div>
                        <h3 class="text-center mb-4">Recent Games</h3>
                        @if($recentGames->isEmpty())
                            <p class="text-center">No recent games available.</p>
                        @else
                            <ul class="list-group">
                                @foreach($recentGames as $game)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            @if ($game->player1 && $game->player2)
                                                <a href="{{ route('games.play', $game->id) }}">
                                                    <strong>{{ $game->player1->name }} vs {{ $game->player2->name }}</strong>
                                                </a><br>
                                            @else
                                                <strong>Player data missing</strong><br>
                                            @endif
                                            Created at: {{ $game->created_at->format('Y-m-d H:i:s') }}<br>
                                            Status: {{ ucfirst($game->status) }}
                                        </div>
                                        @if(auth()->check() && auth()->user()->is_admin)
                                            <form action="{{ route('games.destroy', $game->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this game?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <a href="{{ route('tournaments.create') }}" class="btn btn-primary btn-block">New Tournament</a>
                        </div>
                        <h3 class="text-center mb-4">Recent Tournaments</h3>
                        @if($tournaments->isEmpty())
                            <p class="text-center">No tournaments available.</p>
                        @else
                            <ul class="list-group">
                                @foreach($tournaments as $tournament)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <a href="{{ route('tournaments.show', $tournament->id) }}">
                                                <strong>{{ $tournament->name }}</strong>
                                            </a><br>
                                            Location: {{ $tournament->location }}<br>
                                            Status: {{ $tournament->start_date <= now() && $tournament->end_date >= now() ? 'Ongoing' : 'Upcoming' }}
                                        </div>
                                        @if(auth()->check() && auth()->user()->is_admin)
                                            <form action="{{ route('tournaments.destroy', $tournament->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this tournament?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                            </form>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
