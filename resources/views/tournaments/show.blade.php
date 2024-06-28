@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">{{ $tournament->name }}</h1>
    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Location:</strong> {{ $tournament->location }}</p>
            <p><strong>Website:</strong> <a href="{{ $tournament->website }}" target="_blank">{{ $tournament->website }}</a></p>
            <p><strong>Start Date:</strong> {{ $tournament->start_date }}</p>
            <p><strong>End Date:</strong> {{ $tournament->end_date }}</p>
            <p><strong>Description:</strong> {{ $tournament->description }}</p>
        </div>
    </div>

    <!-- Section for displaying registered players -->
    <div class="card mb-3">
        <div class="card-body">
            <h5>Registered Players</h5>
            <ul class="list-group">
                @forelse($tournament->players as $player)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $player->name }}
                        @if(auth()->check() && auth()->id() == $tournament->creator_id)
                            <form method="POST" action="{{ route('tournaments.remove-player', ['tournament' => $tournament->id, 'user' => $player->id]) }}" onsubmit="return confirm('Are you sure you want to remove this player?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                            </form>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item">No players registered yet.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Section for displaying completed games -->
    <div class="card mb-3">
        <div class="card-body">
            <h5>Completed Games</h5>
            <ul class="list-group">
                @forelse($completedGames as $game)
                    <li class="list-group-item">
                        <strong>{{ $game->player1->name }} vs {{ $game->player2->name }}</strong><br>
                        Completed at: {{ $game->updated_at->format('Y-m-d H:i:s') }}<br>
                        Score: {{ $game->score1 }} - {{ $game->score2 }}
                    </li>
                @empty
                    <li class="list-group-item">No completed games yet.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <!-- Start Game Form, visible only to registered players -->
    @if(auth()->check() && $tournament->players->contains(auth()->user()))
        <div class="card mb-3">
            <div class="card-body">
                <h5>Start New Game</h5>
                <form method="GET" action="{{ route('games.create') }}">
                    <input type="hidden" name="tournament_id" value="{{ $tournament->id }}">
                    <div class="form-group">
                        <label for="opponent">Select Opponent</label>
                        <select name="opponent_id" id="opponent" class="form-control" required>
                            <option value="">Choose Opponent</option>
                            @foreach($tournament->players as $player)
                                @if($player->id != auth()->id())
                                    <option value="{{ $player->id }}">{{ $player->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Start Game</button>
                </form>
            </div>
        </div>
    @endif

    <!-- Search Players Form, visible only to the creator -->
    @if(auth()->check() && auth()->id() == $tournament->creator_id)
        <div class="card mb-3">
            <div class="card-body">
                <h5>Add Players</h5>
                <form method="GET" action="{{ route('tournaments.show', $tournament) }}">
                    @csrf
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Enter player name" required>
                        <button type="submit" class="btn btn-primary mt-2">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Displaying Search Results -->
        @if(request()->has('search') && isset($players))
            <div class="card mb-3">
                <div class="card-body">
                    <h5>Search Results</h5>
                    <ul class="list-group">
                        @forelse($players as $player)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $player->name }}
                                <form method="POST" action="{{ route('tournaments.add-player', ['tournament' => $tournament->id, 'user' => $player->id]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Add to Tournament</button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item">No players found.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @endif
    @endif

    <h3>Comments:</h3>
    @forelse($tournament->comments as $comment)
        <div class="card mb-2">
            <div class="card-body">
                <p>{{ $comment->content }}</p>
                <small>Posted by {{ $comment->user->name }} on {{ $comment->created_at }}</small>
                @if(auth()->check() && auth()->user()->is_admin)
                    <form method="POST" action="{{ route('comments.destroy', $comment) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <p>No comments yet.</p>
    @endforelse

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
</div>
@endsection
