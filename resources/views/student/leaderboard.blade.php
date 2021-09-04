<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex-1 pr-4">
                {{ __('Rankings') }}
            </h2>
            <div>

            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                            Student Name
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Ranking
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Action</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                    @if(isset($leaderboardResults) && !empty($leaderboardResults) && $leaderboardResults->count())
                                        @php $i = 1; @endphp
                                        @foreach($leaderboardResults as $leaderboardResult)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap" >
                                                    <p> {{$i++}}</p>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap" >
                                                    <p> {{$leaderboardResult->user->name ?? 'N/A'}} </p>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap" >
                                                    <p> {{$leaderboardResult->total_points ?? 'N/A'}} </p>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap" colspan="4">
                                                <p class="text-red-300 text-center"> No ranking exists at the moment.</p>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="py-2 px-2">
{{--                        {{ $leaderboardResults->links() }}--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
