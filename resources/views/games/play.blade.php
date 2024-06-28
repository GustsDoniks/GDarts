@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Game: {{ $game->player1->name }} vs {{ $game->player2->name }}</h1>
    <div class="row">
        <!-- Player 1 Section -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <h3>{{ $game->player1->name }}</h3>
                    <p>3-dart Average: <span id="player1-average">0</span></p>
                    <p>Checkout %: <span id="player1-checkout">0</span>%</p>
                    <p>180s: <span id="player1-180s">0</span></p>
                    <p>Leg Score:</p>
                    <textarea id="player1-leg-score" class="form-control mb-2" rows="3" readonly>{{ $game->score1 }}</textarea>
                    <input type="number" id="player1-score" class="form-control mb-2" placeholder="Enter points" max="180">
                    <button id="submit-player1" class="btn btn-primary">Submit Throw</button>
                </div>
            </div>
        </div>

        <!-- Middle Section -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <h3>Game Status</h3>
                    <p id="game-status">{{ $game->game_type == 'one_leg' ? 'One leg game' : 'Set/Leg Results' }}</p>
                    @if ($game->game_type != 'one_leg')
                        <p>Sets: <span id="set-result">0</span></p>
                        <p>Legs: <span id="leg-result">0</span></p>
                    @endif
                    <button id="submit-game" class="btn btn-success mt-3">Submit Game</button>
                </div>
            </div>
        </div>

        <!-- Player 2 Section -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body text-center">
                    <h3>{{ $game->player2->name }}</h3>
                    <p>3-dart Average: <span id="player2-average">0</span></p>
                    <p>Checkout %: <span id="player2-checkout">0</span>%</p>
                    <p>180s: <span id="player2-180s">0</span></p>
                    <p>Leg Score:</p>
                    <textarea id="player2-leg-score" class="form-control mb-2" rows="3" readonly>{{ $game->score2 }}</textarea>
                    <input type="number" id="player2-score" class="form-control mb-2" placeholder="Enter points" max="180">
                    <button id="submit-player2" class="btn btn-primary">Submit Throw</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('submit-player1').addEventListener('click', function () {
            updateScore('player1', document.getElementById('player1-score').value);
        });

        document.getElementById('submit-player2').addEventListener('click', function () {
            updateScore('player2', document.getElementById('player2-score').value);
        });

        document.getElementById('submit-game').addEventListener('click', function () {
            if (confirm('Are you sure you want to submit the game?')) {
                submitGame();
            }
        });

        function updateScore(player, score) {
            score = parseInt(score);
            if (isNaN(score) || score === '') {
                alert('Please enter a score');
                return;
            }
            if (score > 180) {
                alert('Score cannot be greater than 180');
                return;
            }

            fetch('{{ route("games.updateScore", $game->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    player: player,
                    score: score
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (player === 'player1') {
                        document.getElementById('player1-leg-score').value = data.stats.leg_score;
                        document.getElementById('player1-average').innerText = data.stats.average.toFixed(2);
                        document.getElementById('player1-checkout').innerText = data.stats.checkout_percentage.toFixed(2);
                        document.getElementById('player1-180s').innerText = data.stats.one_eighties;
                    } else {
                        document.getElementById('player2-leg-score').value = data.stats.leg_score;
                        document.getElementById('player2-average').innerText = data.stats.average.toFixed(2);
                        document.getElementById('player2-checkout').innerText = data.stats.checkout_percentage.toFixed(2);
                        document.getElementById('player2-180s').innerText = data.stats.one_eighties;
                    }
                    // Update the sets and legs if available
                    if (data.stats.sets_player1 !== undefined) {
                        document.getElementById('set-result').innerText = data.stats.sets_player1;
                    }
                    if (data.stats.legs_player1 !== undefined) {
                        document.getElementById('leg-result').innerText = data.stats.legs_player1;
                    }
                } else {
                    alert('Failed to update score. Please try again.');
                }
            })
            .catch(() => {
                alert('An error occurred while updating the score. Please try again.');
            });
        }

        function submitGame() {
            fetch('{{ route("games.submit", $game->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    window.location.href = '{{ route("home") }}';
                } else {
                    alert('Failed to submit game. Please try again.');
                }
            })
            .catch(() => {
                alert('An error occurred while submitting the game. Please try again.');
            });
        }
    });
</script>
@endsection
