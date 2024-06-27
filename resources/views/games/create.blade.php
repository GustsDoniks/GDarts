@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center mb-4">Start New Game</h1>
        <form action="{{ route('games.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <label for="player1" class="form-label">Player 1 (You)</label>
                            <input type="text" class="form-control" id="player1" name="player1" value="Test" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 offset-md-2">
                    <div class="card">
                        <div class="card-body">
                            <label for="player2" class="form-label">Player 2 (Opponent)</label>
                            <input type="text" class="form-control" id="player2" name="player2" placeholder="Search for an opponent">
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

                    <div class="mb3">
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

            gameTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (document.getElementById('best_of').checked) {
                        legsInput.disabled = false;
                    } else {
                        legsInput.disabled = true;
                        legsInput.value = '';
                    }
                });
            });

            setTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (document.getElementById('sets_of').checked) {
                        legsInSetInput.disabled = false;
                    } else {
                        legsInSetInput.disabled = true;
                        legsInSetInput.value = '';
                    }
                });
            });
        });
    </script>
@endsection


@section('scripts')
    <script>
        function enableDisableTextBoxes() {
            const bestOfRadio = document.getElementById('best_of');
            const legsInput = document.getElementById('legs');

            const setsOfRadio = document.getElementById('sets_of');
            const legsInSetInput = document.getElementById('legs_in_set');

            if (bestOfRadio.checked) {
                legsInput.disabled = false;
                alert('Best of selected, enabling Number of legs input');
            } else {
                legsInput.disabled = true;
                legsInput.value = '';
                alert('One leg game selected, disabling Number of legs input');
            }

            if (setsOfRadio.checked) {
                legsInSetInput.disabled = false;
                alert('Sets of selected, enabling Legs in a set input');
            } else {
                legsInSetInput.disabled = true;
                legsInSetInput.value = '';
                alert('No sets selected, disabling Legs in a set input');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const bestOfRadio = document.getElementById('best_of');
            const oneLegRadio = document.getElementById('one_leg');
            const setsOfRadio = document.getElementById('sets_of');
            const noSetsRadio = document.getElementById('no_sets');

            enableDisableTextBoxes();

            bestOfRadio.addEventListener('change', enableDisableTextBoxes);
            oneLegRadio.addEventListener('change', enableDisableTextBoxes);
            setsOfRadio.addEventListener('change', enableDisableTextBoxes);
            noSetsRadio.addEventListener('change', enableDisableTextBoxes);
        });
    </script>
@endsection
