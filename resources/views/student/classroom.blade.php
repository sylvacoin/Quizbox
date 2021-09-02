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
                <div>
                    <div class="p-6 bg-white border-b border-gray-200 ">
                        <div class="font-bold text-md mb-2 uppercase">Class LeaderBoard</div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Classroom
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ranking
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
{{--                            <tr>--}}
{{--                                <td class="px-6 py-4 whitespace-nowrap" >--}}
{{--                                    <p> PHP </p>--}}
{{--                                </td>--}}
{{--                                <td class="px-6 py-4 whitespace-nowrap" >--}}
{{--                                    100th--}}
{{--                                </td>--}}

{{--                            </tr>--}}
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap" colspan="4">
                                    <p class="text-red-300 text-center"> No ranking at the moment.</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 bg-white border-b border-gray-200 mt-4">
                        <div class="font-bold text-md mb-2 uppercase">Class LeaderBoard</div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Classroom
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ranking
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
{{--                            <tr>--}}
{{--                                <td class="px-6 py-4 whitespace-nowrap" >--}}
{{--                                    <p> PHP </p>--}}
{{--                                </td>--}}
{{--                                <td class="px-6 py-4 whitespace-nowrap" >--}}
{{--                                    100th--}}
{{--                                </td>--}}

{{--                            </tr>--}}
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap" colspan="4">
                                    <p class="text-red-300 text-center"> No ranking at the moment.</p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="font-bold text-xl mb-2">Topic: @unless(!$notes) {{$notes->title}} @endunless</div>
                    <div class="overflow-hidden mt-8">
                        <p>@unless(!$notes) {{$notes->description}} @endunless</p>
                        <br>
                        <p>@unless(!$notes) {{$notes->note}} @endunless</p>
                    </div>
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
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        const roomId = '{{ $notes->classroom->room_id }}';
        const lessonId = '{{ $notes->id }}';
        const sender = {{ Auth::user()->id }}

        $('.stats').hide();
        $(document).on('click', '.row-trigger', function () {
            const id = $(this).data('row');
            console.log(id)
            $('.row'+id).toggle('slow');
        });

        $(document).ready(function(){

            $.ajax({
                url: `/chat/read/${roomId}`,
                method:'get',
                success: function(resp)
                {
                    console.log(resp.data);
                    var data = resp.data;

                    $.each(data, function(idx, history){
                        if (history.sender_id !== sender)
                        {
                            $('#chatWindow')
                                .append(`<div class="chat-message">
                                    <div class="flex items-end">
                                        <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                                            <div><span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">${history.message}</span></div>
                                        </div>
                                        <img src="https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144" alt="My profile" class="w-6 h-6 rounded-full order-1">
                                    </div>
                                </div>`);
                        }else{
                            $('#chatWindow')
                                .append(`<div class="chat-message">
                                    <div class="flex items-end justify-end">
                                        <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end">
                                            <div><span class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-white ">${history.message}</span></div>
                                        </div>
                                        <img src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144" alt="My profile" class="w-6 h-6 rounded-full order-2">
                                    </div>
                                </div>`);
                        }
                    });
                },
                error: function(e)
                {
                    console.log(e.message);
                }
            });

            const classroomStatus= '{{$notes->classroom->status}}';

            // Enable pusher logging - don't include this in production
            Pusher.logToConsole = true;

            var pusher = new Pusher('1034701475721d582d8b', {
                cluster: 'us2'
            });

            if (classroomStatus == 1){
                window.Echo.private('channel-'+roomId).listen('SendMessageEvent', (e) => {
                    console.log(e.room_url);
                });
            }

            var channel = pusher.subscribe('channel-'+roomId);
            channel.bind('qp_groupchat', function(data) {
                if ( data.message_type === 'chat')
                {
                    $('#chatWindow')
                        .append(`<div class="chat-message">
                                    <div class="flex items-end">
                                        <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                                            <div><span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">${data.message}</span></div>
                                        </div>
                                        <img src="https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144" alt="My profile" class="w-6 h-6 rounded-full order-1">
                                    </div>
                                </div>`);
                }else{
                    alert(JSON.stringify(data));
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
                    $('#chatWindow')
                        .append(`<div class="chat-message">
                                    <div class="flex items-end justify-end">
                                        <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end">
                                            <div><span class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-white ">${message}</span></div>
                                        </div>
                                        <img src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144" alt="My profile" class="w-6 h-6 rounded-full order-2">
                                    </div>
                                </div>`);
                },
                error: function(e)
                {
                    console.log(e.message);
                }
            });
        });
    </script>
</x-app-layout>

