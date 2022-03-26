@extends('layouts.app')

@push('styles')
<style type="text/css">
    @keyframes rotate {
        from {
            transform: rotate(0deg)
        }

        to {
            transform: rotate(360deg)
        }
    }

    .refresh {
        animation: rotate 1.5s linear infinite;
    }

</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Game</div>

                <div class="card-body text-center">
                    <div class="text-center">
                        <img id="circle" width="250" class="" src="{{asset('images/game/circle.png')}}" alt="">

                        <p id="winner" class="display-1 d-none text-primary">10</p>
                    </div>

                    <hr>

                    <div class="text-center">
                        <div for="bet" class="font-weight-bold h5">
                            Tu Apuesta
                        </div>
                        <select name="bet" id="bet" class="custom-select col-auto">
                            <option value="" selected>Sin seleccion</option>
                            @foreach (range(1,12) as $n)
                            <option value="{{$n}}">{{$n}}</option>

                            @endforeach
                        </select>
                    </div>

                    <hr>

                    <p class="font-weight-bold h5">Tiempo Restante</p>
                    <p id="timer" class="h5 text-danger">Esperando para empezar...</p>

                    <hr>

                    <p id="result" class="h1"></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.onload = function () {

        const circleElement = document.getElementById('circle');
        const timerElement = document.getElementById('timer');
        const winnerElement = document.getElementById('winner');
        const betElement = document.getElementById('bet');
        const resultElement = document.getElementById('result');
        Echo.channel('game')
            .listen('.RemainingTimeChanged', (e) => {

                timerElement.innerText = e.time;
                circleElement.classList.add('refresh');
                resultElement.innerText = '';
                winnerElement.classList.add('d-none');
                winnerElement.classList.remove('text-success');
                winnerElement.classList.remove('text-danger');

            })
            .listen('.WinnerNumberGenerated', (e) => {
                let winner = e.number;
                circleElement.classList.remove('refresh');
                winnerElement.innerText = winner;
                winnerElement.classList.remove('d-none');
                let bet = betElement[betElement.selectedIndex].value;
                if (bet == winner) {
                    resultElement.classList.remove('text-danger');
                    resultElement.classList.add('text-success');
                    resultElement.innerText = 'HAS GANADO';
                } else {
                    resultElement.classList.remove('text-success');
                    resultElement.classList.add('text-danger');
                    resultElement.innerText = 'HAS PERDIDO';
                }
            });
    };

</script>
@endpush
