<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
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
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mb-4">
                    <div class="p-6 sm:px-20 bg-white border-b border-gray-200">


                        <div class="mt-8 text-2xl">
                            Welcome {{ \Illuminate\Support\Facades\Auth::user()->name ?? 'Stranger' }}!
                        </div>

                        <div class="mt-6 text-gray-500">
                            Get started by enrolling in a classroom. Join live sessions to recieve lectures and participate in quizzes
                        </div>
                    </div>
                </div>

{{--            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">--}}
{{--                    <div class="p-6 bg-white border-b border-gray-200 md:col-span-2 md:row-span-2">--}}
{{--                        <div class="font-bold text-xl mb-2">Subscribe to classroom</div>--}}

{{--                        <table class="min-w-full divide-y divide-gray-200">--}}
{{--                            <thead class="bg-gray-50">--}}
{{--                            <tr>--}}
{{--                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                    S/N--}}
{{--                                </th>--}}
{{--                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                    Classroom--}}
{{--                                </th>--}}
{{--                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                    Tutor--}}
{{--                                </th>--}}
{{--                                <th scope="col" class="relative px-6 py-3">--}}
{{--                                    <span class="sr-only">Action</span>--}}
{{--                                </th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody class="bg-white divide-y divide-gray-200">--}}
{{--                            @if(isset($classrooms) && !empty($classrooms) && $classrooms->count())--}}
{{--                                @php $i = 1; @endphp--}}
{{--                                @foreach($classrooms as $classroom)--}}
{{--                                    <tr>--}}
{{--                                        <td class="px-6 py-4 whitespace-nowrap" >--}}
{{--                                            <p> {{$i++}}</p>--}}
{{--                                        </td>--}}
{{--                                        <td class="px-6 py-4 whitespace-nowrap" >--}}
{{--                                            <p> {{$classroom->title ?? 'N/A'}} </p>--}}
{{--                                        </td>--}}
{{--                                        <td class="px-6 py-4 whitespace-nowrap" >--}}
{{--                                            <p> {{$classroom->owner->name ?? 'N/A'}} </p>--}}
{{--                                        </td>--}}
{{--                                        <td class="px-6 py-4 whitespace-nowrap" >--}}
{{--                                            <x-link-button :href="route('classroom.subscribe', $classroom->id)" class="text-xs">--}}
{{--                                                <i class="fa fa-eye"></i>--}}
{{--                                                {{ __('Subscribe') }}--}}
{{--                                            </x-link-button>--}}
{{--                                        </td>--}}

{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                            @else--}}
{{--                                <tr>--}}
{{--                                    <td class="px-6 py-4 whitespace-nowrap" colspan="4">--}}
{{--                                        <p class="text-red-300 text-center"> No classroom exists at the moment.</p>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endif--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                    <div class="p-6 bg-white border-b border-gray-200 ">--}}
{{--                        <div class="font-bold text-xl mb-2">LeaderBoard</div>--}}
{{--                        <table class="min-w-full divide-y divide-gray-200">--}}
{{--                            <thead class="bg-gray-50">--}}
{{--                            <tr>--}}
{{--                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                    Classroom--}}
{{--                                </th>--}}
{{--                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                    Ranking--}}
{{--                                </th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody class="bg-white divide-y divide-gray-200">--}}
{{--                            <tr>--}}
{{--                                <td class="px-6 py-4 whitespace-nowrap" >--}}
{{--                                    <p> PHP </p>--}}
{{--                                </td>--}}
{{--                                <td class="px-6 py-4 whitespace-nowrap" >--}}
{{--                                    100th--}}
{{--                                </td>--}}

{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td class="px-6 py-4 whitespace-nowrap" colspan="4">--}}
{{--                                    <p class="text-red-300 text-center"> No ranking at the moment.</p>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                    <div class="p-6 bg-white border-b border-gray-200 ">--}}
{{--                        <div class="font-bold text-xl mb-2">Active classrooms</div>--}}
{{--                        <table class="min-w-full divide-y divide-gray-200">--}}
{{--                            <thead class="bg-gray-50">--}}
{{--                            <tr>--}}
{{--                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                    S/N--}}
{{--                                </th>--}}
{{--                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">--}}
{{--                                    Classroom--}}
{{--                                </th>--}}
{{--                                <th scope="col" class="relative px-6 py-3">--}}
{{--                                    <span class="sr-only">Action</span>--}}
{{--                                </th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody class="bg-white divide-y divide-gray-200">--}}
{{--                            @if(isset($liveClassrooms) && !empty($liveClassrooms) && $liveClassrooms->count())--}}
{{--                                @php $i = 1; @endphp--}}
{{--                                @foreach($liveClassrooms as $liveClass)--}}
{{--                                    <tr>--}}
{{--                                        <td class="px-6 py-4 whitespace-nowrap" >--}}
{{--                                            <p> {{ $i++ }}</p>--}}
{{--                                        </td>--}}
{{--                                        <td class="px-6 py-4 whitespace-nowrap" >--}}

{{--                                            <p> {{ $liveClass->title }} </p>--}}
{{--                                        </td>--}}
{{--                                        <td class="px-6 py-4 whitespace-nowrap" >--}}
{{--                                            <x-link-button :href="route('classroom.join', $liveClass->room_id)" class="text-xs">--}}
{{--                                                <i class="fa fa-eye"></i>--}}
{{--                                                {{ __('Join') }}--}}
{{--                                            </x-link-button>--}}
{{--                                        </td>--}}

{{--                                    </tr>--}}
{{--                                @endforeach--}}
{{--                            @else--}}
{{--                                <tr>--}}
{{--                                    <td class="px-6 py-4 whitespace-nowrap" colspan="4">--}}
{{--                                        <p class="text-red-300 text-center"> No active classroom at the moment.</p>--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
{{--                            @endif--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-4">
                <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2">
                    <div class="p-6">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><a href="{{ route('classrooms.find') }}">Enroll for a class</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-sm text-gray-500">
                                Search for favorite class and enroll to join live sessions
                            </div>

                            <a href="{{ route('classrooms.find') }}">
                                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                                    <div>Enroll Now</div>

                                    <div class="ml-1 text-indigo-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 md:border-t-0 md:border-l">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><a href="{{ route('leaderboard.student.classes') }}">Leaderboard</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-sm text-gray-500">
                                See top 10 highest ranking student for each classroom
                            </div>

                            <a href="{{ route('leaderboard.student.classes') }}">
                                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                                    <div>View Rankings</div>

                                    <div class="ml-1 text-indigo-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">
                                <a href="{{ route('classrooms.my-classes') }}">Live Session</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-sm text-gray-500">
                                Join live classes that are available to you
                            </div>
                            <a href="{{ route('classrooms.my-classes') }}">
                                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                                    <div>Join Now</div>

                                    <div class="ml-1 text-indigo-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="p-6 border-t border-gray-200 md:border-l">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-400"><path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold">
                                <a href="{{ route('profile.show') }}">Account</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-sm text-gray-500">
                                Manage your account preferences and security
                            </div>
                            <a href="{{ route('profile.show') }}">
                                <div class="mt-3 flex items-center text-sm font-semibold text-indigo-700">
                                    <div>Manage Account</div>

                                    <div class="ml-1 text-indigo-500">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
