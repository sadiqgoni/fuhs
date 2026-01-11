@extends('components.layouts.app')
@section('body')
    <div class="bg-white w-full max-w-md sm:max-w-lg md:max-w-xl lg:max-w-2xl rounded-3xl shadow-xl p-8 sm:p-10 text-center">
        {{-- Profile Picture --}}
        <div class="flex justify-center mb-6">
            @if(is_null($user->profile_picture))
                <i class="fa fa-3x fa-user-circle w-24 h-24 sm:w-28 sm:h-28 rounded-full border-4 border-blue-200 shadow-lg object-cover"></i>
            @else
                <img src="{{ asset('storage/'.$user->profile_picture) }}"
                     alt="Profile Picture"
                     class="w-24 h-24 sm:w-28 sm:h-28 rounded-full border-4 border-blue-200 shadow-lg object-cover">
            @endif

        </div>

        {{-- Welcome Text --}}
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Welcome, {{ $user->full_name ?? 'Guest' }}!</h1>
{{--        <p class="text-sm sm:text-base text-gray-500 mb-6">We're happy to have you on board.</p>--}}

        {{-- Links Section --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 text-sm sm:text-base my-4 py-5">
            <a href="{{ route('staff.dashboard') }}"
               class="bg-blue-600 text-dark py-2 px-4 rounded-xl hover:bg-blue-700 transition shadow">
                Dashboard
            </a>
            <a href="{{ route('payroll.request') }}"
               class="bg-gray-100 text-dark-800 py-2 px-4 rounded-xl hover:bg-gray-200 transition shadow">
                Request Report
            </a>
            <a href="{{ route('staff.profile') }}"
               class="bg-gray-100 text-dark py-2 px-4 rounded-xl hover:bg-gray-200 transition shadow">
                Profile
            </a>
        </div>
    </div>

@endsection

@section('title')
    Staff Dashboard
@endsection
@section('page_title')
    Staff Dashboard
@endsection
