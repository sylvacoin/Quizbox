
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex-1 pr-4">
                {{ __($lesson->name . ' Lesson Details') }}
            </h2>
            <div class="flex flex-row">
                <x-jet-button onClick="window.history.back()" class="mr-2">
                    {{ __('Back') }}
                </x-jet-button>
                @if( $lesson->classroom->status == 0)
                    <form method="POST" action="{{ route('classrooms.start', [$lesson->id, $lesson->classroom->room_id]) }}">
                        @csrf
                        <x-jet-button class="bg-red-500">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
                <div class="p-6 bg-white border-b border-gray-200 md:row-span-2">
                    <section class="w-full px-4 flex flex-col bg-white rounded-r-3xl">
                        <div class="flex justify-between items-center h-24 border-b-2 mb-8">
                            <div class="flex space-x-4 items-center">
                                <h1 class="font-bold text-2xl">@unless(!$lesson) {{$lesson->title}} @endunless</h1>
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
                        <div class="overscroll-auto h-96 w-full flex flex-col space-y-4 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
                                <article class="mt-3 text-gray-500 leading-7 tracking-wider h-96 overscroll-auto">
                                    @unless(!$lesson) {{$lesson->description}} @endunless
                                </article>
                                <article class="mt-3 text-gray-500 leading-7 tracking-wider">
                                    @unless(!$lesson) {{$lesson->note}} @endunless
                                </article>
                        </div>
                        <div>
                            <ul class="flex flex-col space-x-4 mt-12">
                                @if ( $lesson->attachments && count($lesson->attachments) > 0)
                                    @foreach( $lesson->attachments as $k => $attachment)
                                        <li class="h-10 flex flex-row items-center justify-between">
                                            <div class="flex flex-row items-center">
                                                <div class="w-10 h-10 border rounded-lg p-1 cursor-pointer transition duration-200 text-gray-300 hover:bg-gray-100">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                    </svg>
                                                </div>
                                                <div class="px-4 text-gray-500 leading-7 tracking-wider">
                                                    <p>{{ isset($attachment->name) ? $attachment->name : 'File '.$k  }}</p>
                                                </div>
                                            </div>
                                            <x-jet-button class="flex justify-self-end" href="{{ route('lessons.download', $attachment->id) }}">Download</x-jet-button>
                                        </li>
                                    @endforeach
                                @endif

                            </ul>
                        </div>
                    </section>
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
                                    <div class="absolute right-0 items-center inset-y-0 sm:flex" x-data>
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
                        <!-- Modal1 -->
                        <div
                            class="fixed inset-0 w-full h-full z-20 bg-black bg-opacity-50 duration-300 overflow-y-auto"
                            x-show="showModal"
                            x-transition:enter="transition duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                        >
                            <div class="relative sm:w-3/4 md:w-1/2 lg:w-1/2 mx-2 sm:mx-auto my-10 opacity-100">
                                <div
                                    class="relative bg-white shadow-lg rounded-md text-gray-900 z-20"
                                    @click.away="showModal = false"
                                    x-show="showModal"
                                    x-transition:enter="transition transform duration-300"
                                    x-transition:enter-start="scale-0"
                                    x-transition:enter-end="scale-100"
                                    x-transition:leave="transition transform duration-300"
                                    x-transition:leave-start="scale-100"
                                    x-transition:leave-end="scale-0"
                                >
                                    <header class="flex items-center justify-between p-2">
                                        <h2 class="font-semibold">Quiz List</h2>
                                        <button class="focus:outline-none p-2 close" @click="showModal = false">
                                            <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                                                <path
                                                    d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"
                                                ></path>
                                            </svg>
                                        </button>
                                    </header>
                                    <main class="p-2 text-center overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50 sm:invisible">
                                            <tr class="bg-teal-400 flex flex-col flex-no wrap sm:table-row rounded-l-lg sm:rounded-none mb-2 sm:mb-0">
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    S/N
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Type
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
                                                        <td class="px-6 py-4 whitespace-nowrap" colspan="3">
                                                            <p>{{ $quiz->option_type }}</p>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap" >
                                                            @unless( $lesson->classroom->status == 0 )
                                                                <x-jet-link-button href="javascript:void(0)" class="text-xs ask" data-id="{{ $quiz->id }}">
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
                                                        <p class="text-red-300 text-center"> No quiz exists at the moment.</p>
                                                    </td>
                                                </tr>
                                            @endif
                                            </tbody>
                                        </table>
                                    </main>
                                    <footer class="flex justify-center p-2">
                                        <button
                                            class="bg-red-600 font-semibold text-white p-2 w-32 rounded-full hover:bg-red-700 focus:outline-none focus:ring shadow-lg hover:shadow-none transition-all duration-300"
                                            @click="showModal = false"
                                        >
                                            Go back
                                        </button>
                                    </footer>
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
                                S/N
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ranking
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="ranking">


                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <script>
        const roomId = '{{ $lesson->classroom->room_id }}';
        const rankingID = 'channel-ranking-{{ $lesson->classroom->id }}';
        const classID = '{{$lesson->classroom->id}}';
        const lessonId = '{{ $lesson->id }}';
        const sender = {{ Auth::user()->id }};
        const classroomStatus= '{{$lesson->classroom->status}}';

        $('.stats').hide();
        $(document).on('click', '.row-trigger', function () {
            const id = $(this).data('row');
            console.log(id)
            $('.row'+id).toggle('slow');
        });

        function appendNotification(message) {
            let shouldScroll = canvas.scrollTop + canvas.clientHeight === canvas.scrollHeight;

            $('#chatWindow')
                .append(`<div class="flex justify-center"> <span class="text-gray-500 text-xs">${message}</span> </div>`);

            if (!shouldScroll) {
                scrollToBottom();
            }
        }

        $(document).ready(function(){
            scrollToBottom();
            //GET CHAT
            $.ajax({
                url: `/chat/read/${roomId}`,
                method:'get',
                success: function(resp)
                {
                    var data = resp.data;

                    $.each(data, function(idx, history){
                        if (history.message_type === 'chat')
                        {
                            appendMessage(history.message, history.sender_id);
                        } else if (history.message_type === 'notification'){
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

            //GET RANKING
            $.ajax({
                url: `/leaderboard/read-rankings/${classID}`,
                method:'get',
                success: function(resp)
                {
                    if (resp.success === true)
                    {
                        var data = resp.data;
                        displayRanking( data );
                    }else{
                        $('#ranking').html(`<tr>
                                            <td class="px-6 py-4 whitespace-nowrap" colspan="4">
                                                <p class="text-red-300 text-center"> No ranking exists at the moment.</p>
                                            </td>
                                        </tr>`);
                    }
                },
                error: function(e)
                {
                    console.log(e.message);
                }
            });

            // Enable pusher logging - don't include this in production
            // Pusher.logToConsole = false;
            //
            // var pusher = new Pusher('1034701475721d582d8b', {
            //     cluster: 'us2'
            // });

            var channel = window.pusher.subscribe('channel-'+roomId);
            channel.bind('qp_groupchat', function(data) {
                if ( data.message.message_type === 'chat')
                {
                    appendMessage(data.message.message, data.message.sender_id);
                } else if (data.message.message_type === 'notification'){
                    appendNotification(data.message.message);
                }else{
                    let records = JSON.parse(data.message.message);
                    appendQuestion(records.id, records.question, records.options, data.message.sender_id);
                }
                scrollToBottom();
            });

            var rankingChannel = window.pusher.subscribe(rankingID);
            rankingChannel.bind('qp_rankings', function(data) {
                console.log(data);
                displayRanking(data.ranking);
            });
        });

        function displayRanking( data)
        {
            let content = '';
            $.each(data, function(idx, ranking){
                content += `<tr>
                                <td class="px-6 py-4 whitespace-nowrap" ><p> ${ idx + 1 } </p></td>
                                <td class="px-6 py-4 whitespace-nowrap" ><p> ${ ranking.name } </p></td>
                                <td class="px-6 py-4 whitespace-nowrap" > ${ranking.point}</td>
                            </tr>`
            });
            $('#ranking').html(content);
        }

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

                choices += `<x-jet-radio-button  id="${ el.option_key }${quid}" option="${ el.option_key }" name="answers[${quid}]" data-id="${quid}"></x-jet-radio-button>`;
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

        function scrollToBottom() {
            canvas.scrollTop = canvas.scrollHeight + 1000;
        }

        $(document).on('click', '.ask', function(e){
            e.preventDefault();
            let quid = $(this).data('id');

            $.ajax({
                url: `/chat/ask`,
                data: {
                    "_token": "{{ csrf_token() }}",
                    'sender_id': sender,
                    'room_url': roomId,
                    'lesson_id': lessonId,
                    'question_id': quid
                } ,
                method:'post',
                success: function()
                {
                    $('.close').click();
                    //$('#textarea').val('');
                },
                error: function(e)
                {
                    console.log(e.message);
                }
            });
        })

        $(document).on('keypress', '#textarea', function(e){
            if (e.keyCode === 13)
            {
                $('#sendMessage').click();
            }
        })

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
                },
                error: function(e)
                {
                    console.log(e.message);
                }
            });
        });
    </script>
</x-app-layout>

