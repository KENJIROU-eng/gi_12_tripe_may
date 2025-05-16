@extends('layouts.app')

@section('title','Post Show')

@section('content')


<div class="bg-gray-200 min-h-screen flex justify-center ">
    <div class="w-3/4 bg-white bg-opacity-90 shadow-lg p-10 px-16 rounded h-[880px] mt-5 mx-auto sm:px-6 lg:px-8">
        <input type="text" placeholder="search" class="border border-gray-300 rounded px-4 py-2 mt-2 w-2/3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <a href="#" class="no-underline text-end "><i class="fa-solid fa-plus ml-auto"></i></a>

        {{--contents--}}
        <div x-data="{ showModal: false }" class="container shadow-lg w-3/5 h-5/6 border mx-auto mt-8">
            <div class="row items-center flex mt-4">
                <div class="col-auto bg-gray-600 rounded-full w-14 h-14 ml-4"></div>
                <div class="col-auto ml-2">username</div>
                <div class="col ml-auto mr-6">
                    <x-dropdown align="right" width="46" >
                        <x-slot name="trigger">
                            <i class="fa-solid fa-ellipsis cursor-pointer"></i>
                        </x-slot>

                        <x-slot name="content">
                            <a href="#" class="block px-4 py-2 text-sm text-green-700 hover:bg-green-100">Edit</a>
                            <button @click="showModal = true" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">Delete</button>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            {{--title--}}
            <h1 class="font-bold text-center mb-2">Post Title</h1>

            {{--image--}}
            <div class="bg-yellow-100 w-100 h-3/5"></div>

            {{--likes,category--}}
            <div class="row flex items-center mt-2">
                <div class="col-auto mx-4"><i class="fa-regular fa-heart text-red-600 text-3xl"></i></div>
                <div class="col-auto ">1090000</div>
                <div class="col text-end text-blue-400 font-light ml-auto mr-4">#country</div>
            </div>

            {{--description--}}
            <p class="font-light p-4">All prices in this email are subject to availability and may change without notice. Prices are based on a double-occupancy room for check-in: May 11, 2025 and check-out: May 12, 2025, unless stated otherwise.</p>

            @include('posts.modals.delete')
        </div>
    </div>
</div>

@endsection