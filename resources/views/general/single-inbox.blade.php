<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex-1 pr-4">
                {{ __('Inbox') }}
            </h2>
            <div>
                <x-jet-link-button :href="route('inbox.reply', $messageId )">
                    Reply Message
                </x-jet-link-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-x-hidden">
            <main class="bg-gray-200">
                <div class="flex flex-col py-3 w-auto inline-block overflow-y-auto overflow-hidden bg-gray-100">
                    <div>
                        @if ( isset($messages) && $messages->count() > 0 )
                            @foreach( $messages as $message)
                                <div class="shadow-lg pt-4 ml-2 mr-2 rounded-lg">
                                    <a href="#" class="block bg-white py-3 border-t pb-4">
                                        <div class="px-4 py-2 flex  justify-between">
                                            <span class="text-sm font-semibold text-gray-900">{{ $message->sender->name }}</span>
                                            <div class="flex">
                                                <span class="px-4 text-sm font-semibold text-gray-600"> {{ $message->created_at->diffForHumans() }}</span>
                                                <div>
                                                    <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M7.07,18.28C7.5,17.38 10.12,16.5 12,16.5C13.88,16.5 16.5,17.38 16.93,18.28C15.57,19.36 13.86,20 12,20C10.14,20 8.43,19.36 7.07,18.28M18.36,16.83C16.93,15.09 13.46,14.5 12,14.5C10.54,14.5 7.07,15.09 5.64,16.83C4.62,15.5 4,13.82 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,13.82 19.38,15.5 18.36,16.83M12,6C10.06,6 8.5,7.56 8.5,9.5C8.5,11.44 10.06,13 12,13C13.94,13 15.5,11.44 15.5,9.5C15.5,7.56 13.94,6 12,6M12,11A1.5,1.5 0 0,1 10.5,9.5A1.5,1.5 0 0,1 12,8A1.5,1.5 0 0,1 13.5,9.5A1.5,1.5 0 0,1 12,11Z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="px-4 py-2 text-sm font-semibold text-gray-700"">
                                        {{ $message->message }}
                                        </p>
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            $('#classroom-panel').hide();
        });

        $(document).on('click', '#btn-classroom-toggle', function (e) {
            e.preventDefault();
            $('#classroom-panel').toggle();
        })
    </script>
</x-app-layout>
