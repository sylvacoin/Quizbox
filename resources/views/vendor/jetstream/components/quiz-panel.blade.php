<div class="px-2 w-full min-h-32">
    <div class="mb-2">
        <label for="question" class="block text-gray-700 text-sm lg:text-base font-semibold mb-2">
            {{ $question }}
        </label>
        <div for="options" class="block text-gray-500 lg:text-base mb-2 tracking-wider">
            <ul class="flex flex-col">
                @if ( !empty( $options ) )
                @foreach($options as $option)
                <li class="py-2 flex flex-row">
                    <span class="text-semibold mx-2"> {{ $option->option_key }}.</span>
                    <p class="flex-1 text-xs">{{ $option->option_value }}</p>
                </li>
                @endforeach
                @endif
            </ul>
        </div>
        <x-jet-radio-button-wrapper>
            @if ( !empty( $options ) )
                @foreach($options as $option)
            <x-jet-radio-button option="{{ $option->option_key }}" name="{{$name}}"></x-jet-radio-button>
                @endforeach
            @endif
        </x-jet-radio-button-wrapper>

    </div>
</div>
