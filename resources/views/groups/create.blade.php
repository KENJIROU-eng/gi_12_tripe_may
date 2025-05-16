@extends('layouts.app')

@section('title','Create Group')

@section('content')

<div class= "mt-5 h-[880px]">
    <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
            <div class="p-6 text-black dark:text-gray-100">
                {{-- title --}}
                <div class="relative flex items-center justify-center h-16 my-5">
                    <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2"></h1>
                </div>
                {{-- contents --}}
                <div class="mx-auto h-full mt-8">
                </div>
                {{-- paginate --}}
                <div class="flex justify-center">
                </div>
            </div>
        </div>
    </div>
</div>

@endsection