<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex-1 pr-4">
                {{ __('Inbox') }}
            </h2>
            <div>
                <x-jet-link-button :href="route('inbox.create')">
                    Create Message
                </x-jet-link-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-x-hidden">
            <div class="bg-gray-200">
                <div class="bg-blue-100 mb-2">
                    <p class="px-4 py-4">Click on message to see message trail</p>
                </div>
                <div class="overflow-y-auto overflow-hidden">
                    <div class="px-4 py-2 flex items-center justify-between border-l border-r border-b">
                        <button class="text-sm flex items-center font-semibold text-gray-600">
                            <span>Sorted by Date</span>
                            <i class="ml-2 fa fa-angle-down justify-between" aria-hidden="true"></i>
                        </button>
                        <button class="text-sm flex items-center font-semibold text-gray-600">
                            <i class="fa fa-bars" aria-hidden="true"></i>
                        </button>
                    </div>
                    <div class="pt-3 pb-4 ">
                        @if ( isset($messages) && $messages->count() > 0 )
                            @foreach( $messages as $message )
                        <a href="{{ route('inbox.single', $message->id) }}" class="block bg-white py-3 border-t">
                            <div class="px-4 py-2 flex  justify-between">
                                <span class="text-sm font-semibold text-gray-900">{{ $message->receiver->name }}</span>
                                <span class="text-sm font-semibold text-gray-600">{{ $message->created_at->diffForHumans() }}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 px-4 py-2">{{ $message->subject }}</span>
                            <p class="px-4 py-2 text-sm font-semibold text-gray-300">{{ Str::limit($message->body, 100) }}</p>
                        </a>
                            @endforeach
                        @else
                            <p>No messages available at the moment</p>
                        @endif
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
        })
    </script>
</x-app-layout>
