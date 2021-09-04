<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex-1 pr-4">
                {{ __($classroom->title) }}
            </h2>
            <div>
                <x-jet-button onClick="window.history.back()">
                    {{ __('Back') }}
                </x-jet-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p>{{session('success')}}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p>{{session('error')}}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
                <div class="px-6 bg-white border-b border-gray-200 col-span-2">
                    <section class="w-full px-4 flex flex-col bg-white rounded-r-3xl">
                        <div class="flex justify-between items-center h-24 border-b-2 mb-8">
                            <div class="flex space-x-4 items-center">
                                <h1 class="font-bold text-2xl">@unless(!$notes) {{$notes->title}} @endunless</h1>
                            </div>
                            <div>
                                <ul class="flex text-gray-400 space-x-4">

                                    <li class="w-6 h-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </li>
                                    <li class="w-6 h-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                        </svg>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <section>
                            <article class="mt-8 text-gray-500 leading-7 tracking-wider">
                                <p>@unless(!$notes) {{$notes->description}} @endunless</p>
                            </article>
                            <article class="mt-8 text-gray-500 leading-7 tracking-wider">
                                <p>@unless(!$notes) {{$notes->note}} @endunless</p>
                            </article>
                            <ul class="flex flex-col space-x-4 mt-12">
                                @if ( $notes->attachments && count($notes->attachments) > 0)
                                    @foreach( $notes->attachments as $k => $attachment)
                                <li class="h-10 flex flex-row items-center justify-between">
                                    <div class="flex flex-row items-center">
                                        <div class="w-10 h-10 border rounded-lg p-1 cursor-pointer transition duration-200 text-gray-300 hover:bg-gray-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                            </svg>
                                        </div>
                                        <div class="px-4 text-gray-500 leading-7 tracking-wider">
                                            <p>File {{ $k }}</p>
                                        </div>
                                    </div>
                                    <x-jet-button class="flex justify-self-end" href="{{$attachment->path}}">Download</x-jet-button>
                                </li>
                                    @endforeach
                                    @endif

                            </ul>
                        </section>
                    </section>
                </div>
                <div class="p-6 bg-white border-b border-gray-200 ">
                    <div class="font-bold text-md mb-2 uppercase">Live Chat
                    </div>

                    <div class="flex-1 justify-between flex flex-col h-96">
                        <div id="chatWindow" class=" w-full flex flex-col space-y-4 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
                            <div class="flex justify-center"> <span class="text-gray-500 text-xs">Class started</span> </div>


                        </div>
                        <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0">
                            <div class="relative flex">
                                <input type="text" placeholder="Write Something" class="w-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 bg-gray-200 rounded-full py-3" id="textarea">
                                <div class="absolute right-0 items-center inset-y-0 hidden sm:flex">
                                    <button id="sendMessage" type="button" class="inline-flex items-center justify-center rounded-full h-12 w-12 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-6 w-6 transform rotate-90">
                                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{--    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>--}}
    <script>
        const roomId = '{{ $notes->classroom->room_id }}';
        const lessonId = '{{ $notes->id }}';
        const sender = {{ Auth::user()->id }};
        const channel = 'channel-{{ $notes->classroom->room_id }}';

        $('.stats').hide();
        $(document).on('click', '.row-trigger', function () {
            const id = $(this).data('row');
            console.log(id)
            $('.row'+id).toggle('slow');
        });

        function appendQuestion(quid, question, options, senderId) {
            let shouldScroll = canvas.scrollTop + canvas.clientHeight === canvas.scrollHeight;
            let questionOptions = '';
            let choices = '';
            $.each(options, function(idx, el ){
                questionOptions += `<li class="py-2 flex flex-row">
                        <span class="text-semibold mx-2">${el.option_key}</span>
                    <p class="flex-1 text-xs">${ el.option_value }</p>
                </li>
                `;

                choices += `<x-jet-radio-button option="${ el.option_key }" name="answers[${quid}]" dataId="${quid}" id="${ el.option_key }${quid}"></x-jet-radio-button>`;
            });

            if (senderId !== sender)
            {
                $('#chatWindow')
                    .append(`<div class="px-2 w-full min-h-32 bg-gray-100 rounded-bl-none py-4">
                            <div class="mb-2">
                                <label for="question" class="block text-gray-700 text-sm lg:text-base font-semibold mb-2">
                                ${question}
                                </label>
                                <div for="options" class="block text-gray-500 lg:text-base mb-2 tracking-wider">
                                    <ul class="flex flex-col">
                                        ${questionOptions}
                                    </ul>
                                </div>
                                <x-jet-radio-button-wrapper>
                                        ${choices}
                                </x-jet-radio-button-wrapper>
                            </div>
                        </div>
                    `);

            }else{
                $('#chatWindow')
                    .append(`<div class="px-2 w-full min-h-32 bg-gray-100 rounded-bl-none py-4">
                            <div class="mb-2">
                                <label for="question" class="block text-gray-700 text-sm lg:text-base font-semibold mb-2">
                                ${question}
                                </label>
                                <div for="options" class="block text-gray-500 lg:text-base mb-2 tracking-wider">
                                    <ul class="flex flex-col">
                                        ${questionOptions}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    `);
            }

            if (!shouldScroll) {
                scrollToBottom();
            }
        }

        $(document).ready(function(){
            scrollToBottom();
            $.ajax({
                url: `/chat/read/${roomId}`,
                method:'get',
                success: function(resp)
                {
                    var data = resp.data;

                    $.each(data, function(idx, history){
                        if (history.message_type === 'chat') {
                            appendMessage(history.message, history.sender_id);
                        }else if (history.message_type === 'notification'){
                            appendNotification(history.message);
                        }else{
                            let records = JSON.parse(history.message);
                            appendQuestion(records.id, records.question, records.options, history.sender_id);
                        }
                    });
                },
                error: function(e)
                {
                    console.log(e.message);
                }
            });

            const classroomStatus= '{{$notes->classroom->status}}';

            var channel = window.pusher.subscribe('channel-'+roomId);
            channel.bind('qp_groupchat', function(data) {
                if (data.message.message_type === 'chat') {
                    appendMessage(data.message.message, data.message.sender_id);
                } else if (data.message.message_type === 'notification'){
                    appendNotification(data.message.message);
                }else{
                    let records = JSON.parse(data.message.message);
                    appendQuestion(records.id, records.question, records.options, data.message.sender_id);
                }
                scrollToBottom();
            });
        });

        const canvas = document.getElementById('chatWindow');

        function appendMessage(message, senderId) {
            let shouldScroll = canvas.scrollTop + canvas.clientHeight === canvas.scrollHeight;
            if (senderId !== sender)
            {
                $('#chatWindow')
                    .append(`<div class="chat-message">
                                    <div class="flex items-end">
                                        <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                                            <div><span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">${message}</span></div>
                                        </div>
                                        <img src="https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144" alt="My profile" class="w-6 h-6 rounded-full order-1">
                                    </div>
                                </div>`);

            }else{
                $('#chatWindow')
                    .append(`<div class="chat-message">
                                    <div class="flex items-end justify-end">
                                        <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end">
                                            <div><span class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-white ">${message}</span></div>
                                        </div>
                                        <img src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144" alt="My profile" class="w-6 h-6 rounded-full order-2">
                                    </div>
                                </div>`);
            }

            if (!shouldScroll) {
                scrollToBottom();
            }
        }

        function appendNotification(message) {
            let shouldScroll = canvas.scrollTop + canvas.clientHeight === canvas.scrollHeight;

            $('#chatWindow')
                .append(`<div class="flex justify-center"> <span class="text-gray-500 text-xs">${message}</span> </div>`);

            if (!shouldScroll) {
                scrollToBottom();
            }
        }

        function scrollToBottom() {
            canvas.scrollTop = canvas.scrollHeight;
        }

        $(document).on('click', '.answer', function(){
           var input = $(this).prev('input');
           var quid = input.data('id');
           var choice = input.val();
            $.ajax({
                url: `/chat/answer`,
                data: {
                    "_token": "{{ csrf_token() }}",
                    "quid": quid,
                    "answer": choice,
                    'sender_id': sender,
                    'room_url': roomId,
                    'lesson_id': lessonId
                } ,
                method:'post',
                success: function(resp)
                {
                    console.log(resp);
                    if (resp.success === true)
                    {
                        $('#textarea').val('');
                        scrollToBottom();
                    }else{
                        alert(resp.message)
                    }

                },
                error: function(e)
                {
                    console.log(e.message);
                }
            });
        });

        $(document).on('click', '#sendMessage', function (e) {
            e.preventDefault();
            var message = $('#textarea').val();

            if (message.trim().length <= 0 )
            {
                alert('Please enter a message first');
                return false;
            }

            $.ajax({
                url: `/chat/send`,
                data: {
                    "_token": "{{ csrf_token() }}",
                    'message': message,
                    'sender_id': sender,
                    'room_url': roomId,
                    'lesson_id': lessonId
                } ,
                method:'post',
                success: function()
                {
                    $('#textarea').val('');
                    scrollToBottom();
                },
                error: function(e)
                {
                    console.log(e.message);
                }
            });
        });
    </script>
</x-app-layout>

