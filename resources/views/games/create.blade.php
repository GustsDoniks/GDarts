@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Start New Game</h1>
        <form action="{{ route('games.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tournament_id" value="{{ $tournament_id }}">
            <div class="row mb-3">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <label for="player1" class="form-label">Player 1 (You)</label>
                            <input type="text" class="form-control" id="player1" name="player1" value="{{ Auth::user()->name }}" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 offset-md-2">
                    <div class="card">
                        <div class="card-body">
                            <label for="player2" class="form-label">Player 2 (Opponent)</label>
                            <input type="text" class="form-control" id="player2" name="player2" placeholder="Search for an opponent" value="{{ $opponent ? $opponent->name : '' }}" autocomplete="off">
                            <div id="search-results" class="list-group"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Game Type</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="game_type" id="one_leg" value="one_leg" checked>
                            <label class="form-check-label" for="one_leg">One leg game</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="game_type" id="best_of" value="best_of">
                            <label class="form-check-label" for="best_of">Best of</label>
                        </div>
                        <input type="number" class="form-control mt-2" id="legs" name="legs" placeholder="Number of legs" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Sets</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="set_type" id="no_sets" value="no_sets" checked>
                            <label class="form-check-label" for="no_sets">No sets</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="set_type" id="sets_of" value="sets_of">
                            <label class="form-check-label" for="sets_of">Sets of</label>
                        </div>
                        <input type="number" class="form-control mt-2" id="legs_in_set" name="legs_in_set" placeholder="Legs in a set" disabled>
                        <input type="number" class="form-control mt-2" id="sets" name="sets" placeholder="Number of sets" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="target_score" class="form-label">Target Score of a Leg</label>
                        <input type="number" class="form-control" id="target_score" name="target_score" value="501">
                    </div>

                    <button type="submit" class="btn btn-primary">Start Game</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gameTypeRadios = document.getElementsByName('game_type');
            const setTypeRadios = document.getElementsByName('set_type');
            const legsInput = document.getElementById('legs');
            const legsInSetInput = document.getElementById('legs_in_set');
            const setsInput = document.getElementById('sets');

            function enableDisableTextBoxes() {
                if (document.getElementById('best_of').checked) {
                    legsInput.disabled = false;
                } else {
                    legsInput.disabled = true;
                    legsInput.value = '';
                }

                if (document.getElementById('sets_of').checked) {
                    legsInSetInput.disabled = false;
                    setsInput.disabled = false;
                } else {
                    legsInSetInput.disabled = true;
                    legsInSetInput.value = '';
                    setsInput.disabled = true;
                    setsInput.value = '';
                }
            }

            gameTypeRadios.forEach(radio => {
                radio.addEventListener('change', enableDisableTextBoxes);
            });

            setTypeRadios.forEach(radio => {
                radio.addEventListener('change', enableDisableTextBoxes);
            });

            enableDisableTextBoxes();

            // AJAX
            const player2Input = document.getElementById('player2');
            const searchResults = document.getElementById('search-results');

            player2Input.addEventListener('input', function() {
                const query = player2Input.value;

                if (query.length > 2) {
                    fetch(`/search-users?query=${query}`)
                        .then(response => response.json())
                        .then(data => {
                            searchResults.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(user => {
                                    const resultItem = document.createElement('a');
                                    resultItem.classList.add('list-group-item', 'list-group-item-action');
                                    resultItem.textContent = user.name;
                                    resultItem.addEventListener('click', function() {
                                        player2Input.value = user.name;
                                        searchResults.innerHTML = '';
                                    });
                                    searchResults.appendChild(resultItem);
                                });
                            } else {
                                searchResults.innerHTML = '<div class="list-group-item">No results found</div>';
                            }
                        });
                } else {
                    searchResults.innerHTML = '';
                }
            });
        });
    </script>
@endsection
