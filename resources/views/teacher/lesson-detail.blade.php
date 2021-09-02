
<x-app-layout>
    <style>
        .scrollbar-w-2::-webkit-scrollbar {
            width: 0.25rem;
            height: 0.25rem;
        }

        .scrollbar-track-blue-lighter::-webkit-scrollbar-track {
            --bg-opacity: 1;
            background-color: #f7fafc;
            background-color: rgba(247, 250, 252, var(--bg-opacity));
        }

        .scrollbar-thumb-blue::-webkit-scrollbar-thumb {
            --bg-opacity: 1;
            background-color: #edf2f7;
            background-color: rgba(237, 242, 247, var(--bg-opacity));
        }

        .scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
            border-radius: 0.25rem;
        }
    </style>

    <script>
        // const el = document.getElementById('messages')
        // el.scrollTop = el.scrollHeight
    </script>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex-1 pr-4">
                {{ __($lesson->name . ' Lesson Details') }}
            </h2>
            <div>
                <x-jet-button onClick="window.history.back()">
                    {{ __('Back') }}
                </x-jet-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ showModal : false }">
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
            <div class="w-min-full mb-4">
                <div class="p-6 bg-white border-b border-gray-200 md:col-span-2 md:row-span-2">
                    <div class="flex flex-row justify-between">
                        <div class="md:flex-1">
                            <div class="font-bold text-xl mb-2">Online Users</div>
                        </div>
                        <div>
                            @if( $lesson->classroom->status == 0)
                                <form method="POST" action="{{ route('classrooms.start', [$lesson->id, $lesson->classroom->room_id]) }}">
                                    @csrf
                                    <x-jet-button>
                                        {{ __('Start Class') }}
                                    </x-jet-button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('classrooms.stop', [$lesson->id, $lesson->classroom->room_id]) }}">
                                    @csrf
                                    <x-jet-button>
                                        {{ __('End Class') }}
                                    </x-jet-button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <div class="p-6 bg-white border-b border-gray-200 md:row-span-2">
                    <div class="font-bold text-xl mb-2">Lesson Notes</div>
                    <div class="shadow overflow-hidden">

                    </div>
                </div>
                @unless($lesson->classroom->status == 0)
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
                                    <div class="absolute right-0 items-center inset-y-0 hidden sm:flex" x-data>
                                        <button @click="showModal = !showModal" class="inline-flex items-center justify-center rounded-full h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                                            <svg stroke="currentColor" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="h-6 w-6 text-gray-600">
                                                <path d="m362.66 59.067c-12.01 0-23.693 1.427-34.89 4.121-30.525-23.723-68.306-36.872-107.267-37.182-47.559-.365-92.381 17.863-126.15 51.365-33.813 33.545-52.434 78.268-52.434 125.929 0 11.784-2.916 23.519-8.433 33.935l-29.997 56.629c-4.87 9.159-4.629 19.972.645 28.926 5.276 8.958 14.621 14.415 24.998 14.597l12.739.002v45.334c0 27.108 22.034 49.162 49.116 49.162h31.923v13.257c0 22.529 18.313 40.857 40.822 40.857h140.299c22.509 0 40.822-18.328 40.822-40.857v-29.358c0-19.503 1.998-39.006 5.949-58.148 3.913.309 7.867.467 11.858.467 82.347 0 149.34-67.074 149.34-149.519 0-82.443-66.994-149.517-149.34-149.517zm-47.806 356.718v29.358c0 5.986-4.855 10.857-10.823 10.857h-140.299c-5.968 0-10.823-4.871-10.823-10.857v-16.927c0-14.519-11.804-26.331-26.315-26.331h-35.607c-10.541 0-19.117-8.596-19.117-19.162v-49.004c0-14.519-11.804-26.331-26.315-26.331h-15.283l29.722-56.112c7.801-14.726 11.924-31.316 11.924-47.978 0-39.603 15.471-76.761 43.563-104.631 28.049-27.827 65.242-42.95 104.783-42.664 25.892.206 51.149 7.23 73.187 20.11-47.595 25.026-80.132 75.016-80.132 132.471 0 68.077 45.678 125.673 107.96 143.681-4.266 20.914-6.425 42.215-6.425 63.52zm47.806-87.68c-65.804 0-119.34-53.616-119.34-119.519s53.535-119.519 119.34-119.519 119.341 53.616 119.341 119.519-53.536 119.519-119.341 119.519z"/>
                                                <path d="m365.506 114.705c-15.573-.779-30.816 5.068-41.817 16.071-5.858 5.858-5.858 15.355 0 21.213 5.857 5.858 15.355 5.858 21.212 0 5.088-5.088 11.876-7.697 19.087-7.322 12.432.63 22.852 10.837 23.721 23.239.781 11.144-5.667 21.311-16.046 25.299-14.358 5.518-24.004 19.742-24.004 35.396 0 8.284 6.716 15 15 15s15-6.716 15-15c0-3.326 1.915-6.297 4.767-7.393 22.77-8.75 36.919-31.014 35.209-55.4-1.942-27.727-24.353-49.696-52.129-51.103z"/>
                                                <path d="m362.66 256.94c-8.284 0-15 6.716-15 15v15.599c0 8.284 6.716 15 15 15s15-6.716 15-15v-15.599c-.001-8.284-6.716-15-15-15z" class="text-gray-600"/>
                                            </svg>
                                        </button>
                                        <button id="sendMessage" type="button" class="inline-flex items-center justify-center rounded-full h-12 w-12 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-6 w-6 transform rotate-90">
                                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal -->
                        <div>
                            <!-- Modal Background -->
                            <div x-show="showModal" class="fixed text-gray-500 flex items-center justify-center overflow-auto z-50 bg-black bg-opacity-40 left-0 right-0 top-0 bottom-0" x-transition:enter="transition ease duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                <!-- Modal -->
                                <div x-show="showModal" class="relative sm:w-3/4 md:w-1/2 lg:w-1/3 mx-2 sm:mx-auto my-10 opacity-100" @click.away="showModal = false" x-transition:enter="transition ease duration-100 transform" x-transition:enter-start="opacity-0 scale-90 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease duration-100 transform" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-90 translate-y-1">
                                    <!-- Title -->
                                    <span class="font-bold block text-2xl mb-3">Questions </span>

                                    <div class="shadow overflow-hidden">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50 sm:invisible">
                                            <tr class="bg-teal-400 flex flex-col flex-no wrap sm:table-row rounded-l-lg sm:rounded-none mb-2 sm:mb-0">
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    S/N
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="4">
                                                    Question
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                            @if(isset($lesson->quizzes) && !empty($lesson->quizzes) && $lesson->quizzes->count())
                                                @php $i = 1; @endphp
                                                @foreach($lesson->quizzes as $quiz)
                                                    <tr class="bg-teal-400 flex flex-col flex-no wrap sm:table-row rounded-l-lg sm:rounded-none mb-2 sm:mb-0">
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            {{$i++}}
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap" colspan="3">
                                                            <p>{{ $quiz->question }}</p>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap" >
                                                            @unless( $lesson->classroom->status == 0 )
                                                                <x-jet-link-button href="#" class="text-xs">
                                                                    <i class="fa fa-eye"></i>
                                                                    {{ __('Ask') }}
                                                                </x-jet-link-button>
                                                                <x-jet-link-button href="#" class="text-xs row-trigger" data-row="{{$i}}">
                                                                    <i class="fa fa-eye"></i>
                                                                    {{ __('Stats') }}
                                                                </x-jet-link-button>
                                                            @endunless
                                                        </td>
                                                    </tr>
                                                    <tr class="stats row{{$i}} bg-teal-400 flex flex-col flex-no wrap sm:table-row rounded-l-lg sm:rounded-none mb-2 sm:mb-0">
                                                        <td class="px-6 py-4 whitespace-nowrap"></td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <p class="font-bold">Options</p>
                                                            @if ( $quiz->option_type == 'multichoice' )
                                                                @foreach( $quiz->quiz_options as $option)
                                                                    <p class="text-xs">{{$option->option_key}}.) {{ $option->option_value }}</p>
                                                                @endforeach
                                                            @elseif ($quiz->option_type == 'boolean')
                                                                <p class="text-xs">A.) True</p>
                                                                <p class="text-xs">B.) False</p>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap" >
                                                            <p><span class="font-bold">Answer : </span> {{$quiz->answer}}</p>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap" colspan="2">
                                                            <p class="font-bold">Stats</p>
                                                            <p class="text-xs">Number answered: 12/14</p>
                                                            <p class="text-xs">Correct answers: 5/14</p>
                                                            <p class="text-xs">Wrong answers: 7/14</p>
                                                        </td>
                                                    </tr>

                                                @endforeach
                                            @else
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap" colspan="4">
                                                        <p class="text-red-300 text-center"> No classroom exists at the moment.</p>
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="text-right space-x-5 mt-5">
                                        <button @click="showModal = !showModal" class="px-4 py-2 text-sm bg-white rounded-xl border transition-colors duration-150 ease-linear border-gray-200 text-gray-500 focus:outline-none focus:ring-0 font-bold hover:bg-gray-50 focus:bg-indigo-50 focus:text-indigo">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endunless
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
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap" >
                                <p> PHP </p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" >
                                100th
                            </td>

                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap" colspan="4">
                                <p class="text-red-300 text-center"> No ranking at the moment.</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        const roomId = '{{ $lesson->classroom->room_id }}';
        const lessonId = '{{ $lesson->id }}';
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

            const classroomStatus= '{{$lesson->classroom->status}}';

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

