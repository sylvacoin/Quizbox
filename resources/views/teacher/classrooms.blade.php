<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex-1 pr-4">
                {{ __('My Classrooms') }}
            </h2>
            <div>
                <x-jet-button id="btn-classroom-toggle">
                    Add Classroom
                </x-jet-button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-2 bg-white border-b border-gray-200 overflow-hidden shadow-sm sm:rounded-lg mb-4" id="classroom-panel">

                <form class="px-8 pt-6 pb-8 mb-4" method="post" action="{{route('classrooms.create')}}">
                    @csrf
                    <div class="flex flex-row">
                        <div class="flex-1 mr-4">
                            <x-jet-label for="classroom" value="{{ __('Classroom Name') }}" />
                            <x-jet-input id="classroom" class="block mt-1 w-full" type="text" name="classroom" :value="old('classroom')" required autofocus autocomplete="classroom" />
                        </div>

                        <div class="inline-flex items-center mt-4">
                            <x-jet-button>
                                Create Classroom
                            </x-jet-button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="p-2 bg-white border-b border-gray-200 overflow-hidden shadow-sm sm:rounded-lg">

                <div class="flex flex-col mb-4">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            S/N
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Classroom Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tutor Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Modified Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Created Date
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Action</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">

                                    @if($classrooms->count() > 0)
                                        @php $i = 1; @endphp
                                        @foreach($classrooms as $classroom)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $i++ }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $classroom->title }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $classroom->owner->name }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{\Carbon\Carbon::parse($classroom->updated_at)->format('d-m-Y H:i A') }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ \Carbon\Carbon::parse($classroom->created_at)->format('d-m-Y H:i A') }}
                                                    </div>
                                                </td>

                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <x-jet-link-button :href="route('classrooms.lessons', $classroom->id)">
                                                        <i class="fa fa-eye"></i>
                                                        {{ __('View') }}
                                                    </x-jet-link-button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap" colspan="5">
                                                <p class="text-red-300 text-center"> No classrooms exists at the moment. create a classroom</p>
                                            </td>
                                        </tr>
                                    @endif

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="py-2 px-2">
                        {{ $classrooms->links() }}
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
