@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card mt-3">
                    <div class="card-header">Received Messages</div>
                    <div class="card-body" id="messageContainer"  style="height: 200px; overflow-y: auto;">
                        <ul class="list-group list-group-flush" id="messageList">
                            @foreach ($messages as $message)
                                <li class="list-group-item">
                                    <span class="font-weight-bold" >{{ $message->user_name }}:</span>
                                    <span>{{ $message->message }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form id="messageForm" method="POST" action="{{ route('messages.store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="message">Message:</label>
                                <textarea id="message" data-name="{{ Auth::user()->name }}" name="message" class="form-control" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('socket.io-4.7.2/client-dist/socket.io.js') }}"></script>
    <script>
        const socket = io('http://192.168.68.123:3000');
        const messageContainer = document.getElementById('messageContainer');

        function scrollToBottom() {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        window.onload = scrollToBottom;

        function appendMessageToList(message, userName) {
            const messageList = document.getElementById('messageList');
            const listItem = document.createElement('li');
            listItem.classList.add('list-group-item');

            const userNameSpan = document.createElement('span');
            userNameSpan.classList.add('font-weight-bold');
            userNameSpan.textContent = userName + ': ';

            const messageSpan = document.createElement('span');
            messageSpan.textContent = message;

            listItem.appendChild(userNameSpan);
            listItem.appendChild(messageSpan);
            messageList.appendChild(listItem);

            scrollToBottom(); // Scroll to the bottom after adding a new message
        }

        socket.on('message', (data) => {
            console.log('Received message:', data);
            appendMessageToList(data.message, data.user_name);
        });

        function sendMessage(event) {
            event.preventDefault();
            
            const messageInput = document.getElementById('message');
            const userName = messageInput.getAttribute('data-name');
            const message = messageInput.value.trim();

            if (message !== '') {
                socket.emit('message', {message:message,user_name:userName});

                // Save message to the database via AJAX
                fetch("{{ route('messages.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: message })
                }).then(response => {
                    if (response.ok) {
                        console.log('Message saved successfully');
                    } else {
                        console.error('Failed to save message');
                    }
                }).catch(error => {
                    console.error('Error occurred while saving message:', error);
                });

                messageInput.value = '';
            } else {
                console.log('Please enter a message.');
            }
        }

        const messageForm = document.getElementById('messageForm');
        messageForm.addEventListener('submit', sendMessage);
        

        document.getElementById('message').addEventListener('keydown', function(event) {
            if (event.keyCode === 13 && !event.shiftKey) {
                sendMessage(event); // Pass the event to sendMessage
            }
        });
    </script>
@endsection