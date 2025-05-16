@extends('layouts.app')

@section('title','Post List')

@section('content')

<div class="bg-gray-200 min-h-screen flex justify-center">
    <div class="w-3/4 bg-white bg-opacity-90 shadow-lg p-10 px-16 rounded h-[880px] mt-5 mx-auto sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-6 text-center">Post List</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-14 mx-auto">
            {{-- @foreach ($posts as $post) --}}
                <div x-data="{ showModal: false }" class="w-11/12 transform transition duration-300 hover:scale-105 hover:shadow-2xl cursor-pointer relative">
                    <a href="#"><div class="bg-yellow-100 p-4 rounded shadow w-auto h-52"></div></a>
                    <div class="flex p-3 items-center">
                        <div class="rounded-full bg-gray-200 w-12 h-12"></div>
                        <h2 class="text-xl font-bold pl-4">Post Title</h2>
                        <i class="fa-regular fa-trash-can ml-auto hover:text-red-500 cursor-pointer"
                            @click="showModal = true"></i>
                    </div>
                    @include('posts.modals.delete')
                </div>
            {{-- @endforeach --}}
        </div>

    </div>
</div>



@endsection