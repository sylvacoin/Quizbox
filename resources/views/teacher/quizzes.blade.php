<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex-1 pr-4">
                {{ __('All Quizzes') }}
            </h2>
            <div>

                <x-jet-link-button :href="route('quiz.create', $lesson->id)">
                    {{ __('Add Question') }}
                </x-jet-link-button>
                <x-jet-link-button :href="route('classrooms.lessons', $lesson->classroom->id)">
                    {{ __('Back') }}
                </x-jet-link-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-2 bg-white border-b border-gray-200 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- This example requires Tailwind CSS v2.0+ -->
                <div class="flex flex-col mb-4">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
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
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            S/N
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quiz
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Quiz Type
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Answer
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Action</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">

                                    @if($quizzes->count() > 0)
                                        @php $i = 1; @endphp
                                        @foreach($quizzes as $quiz)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $i++ }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $quiz->question }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $quiz->option_type }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $quiz->answer }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">

                                                    <form method="POST" action="{{ route('quiz.delete', $quiz->id) }}">
                                                        @csrf
                                                        <x-jet-button onclick="event.preventDefault(); this.closest('form').submit();" class="">
                                                            <i class="fa fa-trash"></i>
                                                            {{ __('Delete') }}
                                                        </x-jet-button>
                                                    </form>
                                                </td>
                                            </tr>

                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap" colspan="3">
                                                <p class="text-red-300 text-center">No quiz exists at the moment. create a quiz</p>
                                            </td>
                                        </tr>
                                    @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="py-2 px-2">
                        {{ $quizzes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
