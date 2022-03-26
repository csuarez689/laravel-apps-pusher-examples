@extends('layouts.app')

@push('styles')
<style type="text/css">
    #users>li {
        cursor: pointer;
    }

</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Chat</div>

                <div class="card-body">
                    <div class="row p-2">
                        <div class="col-10">
                            <div class="row">
                                <div class="col-12 border rounded-lg p-3">
                                    <ul id="messages" class=" list-unstyled  overflow-auto" style="height: 45vh">

                                    </ul>
                                </div>
                            </div>
                            <form action="">
                                <div class="row py-3">
                                    <div class="col-10">
                                        <input id="message" type="text" class="form-control">
                                    </div>
                                    <div class="col-2">
                                        <button id="send" type="submit" class="btn btn-primary btn-block">Enviar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-2">
                            <p> <strong>Conectados</strong></p>
                            <ul id="users" class="  list-unstyled overflow-auto text-info " style="height: 45vh">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function greetUser(id) {
        window.axios.post(`/chat/greet/${id}`).then(res => {}).catch(error => {
            console.log(error);
        });
    }
    window.onload = function () {
        const usersElement = document.getElementById('users');
        const messagesElement = document.getElementById('messages');

        Echo.join('chat')
            .here((users) => {
                users.forEach(user => {
                    let li = document.createElement('li');
                    li.setAttribute('onclick', 'greetUser("' + user.id + '")');
                    li.innerText = user.name;
                    usersElement.appendChild(li);
                });
            })
            .joining((user) => {
                const li = document.createElement('li');
                li.setAttribute('id', user.id);
                li.innerText = user.name;
                usersElement.appendChild(li);
            })
            .leaving((user) => {
                let li = document.getElementById(user.id);
                li.parentNode.removeChild(li);
            })
            .listen('.MessageSent', (e) => {
                let li = document.createElement('li');
                li.innerText = e.user.name + ': ' + e.message;
                messagesElement.appendChild(li);
            });


        const sendElement = document.getElementById('send');
        const messageElement = document.getElementById('message');
        sendElement.addEventListener('click', (e) => {
            e.preventDefault();
            window.axios.post('/chat/message', {
                message: messageElement.value
            }).then(response => {
                messageElement.value = '';
            }).catch(error => {
                console.log(error);
            });
        });



        Echo.private('chat.greet.{{auth()->user()->id}}')
            .listen('.GreetingSent', (e) => {
                let li = document.createElement('li');
                li.classList.add('text-success');
                li.innerText = e.message;
                messagesElement.appendChild(li);
            });


    };

</script>



@endpush
