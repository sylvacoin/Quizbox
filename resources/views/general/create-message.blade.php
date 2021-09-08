<x-app-layout >
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex-1 pr-4">
                {{ __('Create message') }}
            </h2>
            <div>
                <x-jet-button id="btn-send-message">
                    Send Message
                </x-jet-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-2 bg-white border-b border-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-wrap mb-4">
                    <div class="w-full">
                        <div class="py-6 align-middle inline-block min-w-full sm:px-6 lg:px-8">

                            @if(session('success'))
                                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                                    <p>{{session('success')}}</p>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                                    <p>{{session('error')}}</p>
                                </div>
                            @endif
                            <form method="post" action="">
                                @csrf
                                <div class="flex flex-wrap -mx-3 mb-6">
                                    <div class="w-full px-3 mb-6 md:mb-0">
                                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                                            Recipient Email Address
                                        </label>
                                        <input id="recipient" name="recipient" value="{{ isset($sender) ? $sender : (isset($user) ? $user->email : old('recipient') ) }}" class="appearance-none block w-full bg-gray-200 border border-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" type="text">
                                        <p class="text-gray-600 text-xs italic">Separate email address of recipients with ;</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap -mx-3 mb-6">
                                    <div class="w-full px-3 mb-6 md:mb-0">
                                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-first-name">
                                            Subject
                                        </label>
                                        <input id="subject" name="subject" value="{{ isset($message) ? 'RE: '.$message->subject : old('subject') }}" class="appearance-none block w-full bg-gray-200 border border-gray-200 text-gray-700 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" type="text">
                                    </div>
                                </div>
                                <div class="flex flex-wrap -mx-3 mb-6">
                                    <div class="w-full px-3">
                                        <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-password">
                                            Message
                                        </label>
                                        <textarea id="message" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" name="note" rows="7"></textarea>
                                        <p class="text-gray-600 text-xs italic">Make it as long and as crazy as you'd like</p>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#classroom-panel').hide();
        });

        $(document).on('click', '#btn-classroom-toggle', function (e) {
            e.preventDefault();
            $('#classroom-panel').toggle();
        });

        $(document).on('click', '#btn-send-message', function(e){
            // e.preventDefault();
            $.ajax({
                'url': `{{route('inbox.create')}}`,
                'method': 'POST',
                'data': {
                    'subject': $('#subject').val(),
                    'message': $('#message').val(),
                    'receiver_id': $('#recipient').val(),
                    'message_id': `@if( isset($message) ) {{ $message->id }} @endif`,
                    '_token': `{{ csrf_token() }}`
                },
                'success': function(data){
                    alert(JSON.stringify(data));
                    if (data.success)
                    {
                        window.location.href = '{{route('inbox')}}';
                    }else{
                        alert( data.message );
                    }
                },
                error: function(err){
                    console.log(err)
                    alert( err.message );
                }
            })
        });
    </script>

</x-app-layout>
