@extends('layouts.app')

@section('content')
    
    <div class="container" >
        <div class="row justify-content-center">
            <div class="col-md-8">
               
                    <!-- DIRECT CHAT PRIMARY -->
                    <div class="card card-prirary cardutline direct-chat direct-chat-primary" >
                        <div class="card-header">
                            <strong>@ {{ substr(Auth::user()->name, 0, 7) . '..' }}</strong>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body"><div class="direct-chat-messages" id="messageContainer" style="height:600px;">
                            <!-- Conversations are loaded here -->
                            @foreach ($messages as $message)
                                <div class="direct-chat-msg {{ Auth::user()->id === $message->user_id ? 'right': ''; }}">
                                    <div class="direct-chat-infos clearfix">
                                        <span class="direct-chat-name {{ Auth::user()->id === $message->user_id ? 'float-right': 'float-left'; }}">{{ $message->user_name }}</span>
                                        <?php 
                                            $date_string = $message->created_at; 
                                            $timestamp = strtotime($date_string); 
                                        ?>
                                        <span class="direct-chat-timestamp  {{ Auth::user()->id === $message->user_id ? 'float-left': 'float-right'; }}"><?= date("d M g:i A", $timestamp)?></span>
                                    </div>
                                    <!-- /.direct-chat-infos -->
                                    <img class="direct-chat-img" src="{{ $message->image_name != ''? 'dist/img/'.$message->image_name : asset('dist/img/AdminLTELogo.png') }}" alt="Message User Image">
                                    <!-- /.direct-chat-img -->
                                    <div class="direct-chat-text">
                                        {{ $message->message }}
                                    </div>
                                    <!-- /.direct-chat-tex t  {{ Auth::user()->image_name != '' ? 'dist/img/'.Auth::user()->image_name: asset('dist/img/AdminLTELogo.png');  }} -->
                                </div>
                            @endforeach
                            
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <form id="messageForm" method="POST" action="{{ route('messages.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="message">Message:</label>
                                    <textarea id="message" data-name="{{ Auth::user()->name }}" class="form-control" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send</button>
                            </form>
                            <div id="typingStatus"></div>
                        </div>
                    <!-- /.card-footer-->
                    </div>
                    <!--/.direct-chat -->
             
                
            </div>
        </div>
    </div>

    <script src="{{ asset('socket.io-4.7.2/client-dist/socket.io.js') }}"></script>
    <script>
        function formatCustomDate(date) {
            const months = [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ];

            const day = date.getDate();
            const month = months[date.getMonth()];
            const hours = date.getHours();
            const period = hours >= 12 ? 'pm' : 'am';
            const formattedHours = hours % 12 === 0 ? 12 : hours % 12;
            const minutes = ('0' + date.getMinutes()).slice(-2);

            return `${day} ${month} ${formattedHours}:${minutes} ${period}`;
        }
        const currentDate = new Date();
        const formattedDate = formatCustomDate(currentDate);
        let uname = '<?=Auth::user()->name ?>';
        let i_id = '<?=Auth::user()->id ?>';
        let image_name = '<?= Auth::user()->image_name ?>';
        const socket = io('http://192.168.68.123:3000');
        let typingTimer;
        const typingTimeout = 1000;

        const messageContainer = document.getElementById('messageContainer');

        function sendTypingStatus(isTyping) {
            const userName = document.getElementById('message').getAttribute('data-name');
            socket.emit('typing', { user: userName, isTyping: isTyping, user_id: i_id,image_name:image_name});
        }

        document.getElementById('message').addEventListener('input', function(event) {
            clearTimeout(typingTimer);

            if (event.target.value.trim() !== '') {
                sendTypingStatus(true);
            } else {
                sendTypingStatus(false);
            }

            typingTimer = setTimeout(() => {
                sendTypingStatus(false);
            }, typingTimeout);
        });

        socket.on('typing', (data) => {
            if (data.isTyping) {
                const typingIndicator = typings(data.user, data.user_id, data.image_name);
                if (typingIndicator) {
                    typingIndicator.style.display = 'block';
                }
            } else {
                const typingIndicator = document.getElementById(`typing_${data.user_id}`);
                if (typingIndicator) {
                     typingIndicator.remove();
                }
            }
            scrollToBottom();
        });

        function scrollToBottom() {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }

        window.onload = scrollToBottom;

        function appendMessageToList(message, userName,current_date,image_name) {
            let image_def = image_name;
            if (image_name === '') {
                image_def = 'AdminLTELogo.png';
            }
            const alignmentClass = userName === uname ? 'right' : '';
            const nameAlignmentClass = userName === uname ? 'float-right' : 'float-left';
            const timestampAlignmentClass = userName === uname ? 'float-left' : 'float-right';

            const html = `
                <div class="direct-chat-msg ${alignmentClass}">
                    <div class="direct-chat-infos clearfix">
                        <span class="direct-chat-name ${nameAlignmentClass}">${userName}</span>
                        <span class="direct-chat-timestamp ${timestampAlignmentClass}">${current_date}</span>
                    </div>
                    <img class="direct-chat-img" src="{{ asset('dist/img/${image_def}') }}" alt="Message User Image">
                    <div class="direct-chat-text">
                        ${message}
                    </div>
                </div>
            `;
            messageContainer.innerHTML += html;
         

            scrollToBottom(); 
        }
        
        function typings(user, user_id,image_name) {
            let image_def = image_name;
            if (image_name === '') {
                image_def = 'AdminLTELogo.png';
            }
            const typingIndicatorId = `typing_${user_id}`;
            let typingIndicator = document.getElementById(typingIndicatorId);

            if (!typingIndicator) {
                const alignmentClass = user === uname ? 'right' : '';
                const nameAlignmentClass = user === uname ? 'float-right' : 'float-left';
                const html = `
                    <div class="direct-chat-msg ${alignmentClass}" id="${typingIndicatorId}" style="display: none;">
                        <div class="direct-chat-infos clearfix">
                            <span class="direct-chat-name ${nameAlignmentClass}">${user}</span>
                        </div>
                        <img class="direct-chat-img" src="{{ asset('dist/img/${image_def}') }}" alt="Message User Image">
                        <div class="direct-chat-text" style="padding-buttom:10px">
                            <div class="jumping-dots-loader"> <span></span> <span></span> <span></span> </div>
                        </div>
                    </div>
                `;
                messageContainer.insertAdjacentHTML('beforeend', html);

                // Retrieve the typing indicator after adding it
                typingIndicator = document.getElementById(typingIndicatorId);
            }

            return typingIndicator;
        }
        
        socket.on('message', (data) => {
            if (data.user_id != i_id) {
                const typingIndicator = document.getElementById(`typing_${data.user_id}`);
                typingIndicator.remove();
            }
           
            appendMessageToList(data.message, data.user_name,data.formattedDate,data.image_name);

        });

        function sendMessage(event) {
            event.preventDefault();
            
            const messageInput = document.getElementById('message');
            const userName = messageInput.getAttribute('data-name');
            const message = messageInput.value.trim();

            if (message !== '') {
                socket.emit('message', {message:message,user_name:userName,formattedDate:formattedDate,user_id:i_id,image_name:image_name});

                fetch("{{ route('messages.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ message: message })
                }).then(response => {
                    if (response.ok) {
                        // console.log('Message saved successfully');
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
                sendMessage(event);
            }
        });
    </script>
@endsection