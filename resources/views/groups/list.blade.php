@extends('layouts.app')

@section('title','Group List')

@section('content')

<div class= "mt-5 h-[880px]">
    <div class="w-9/10 md:w-4/5 mx-auto sm:px-6 lg:px-8 h-full">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
            <div class="p-6 text-black dark:text-gray-100">
                {{-- title --}}
                <div class="relative flex items-center justify-center h-16 my-5">
                    <h1 class="text-md sm:text-2xl lg:text-3xl 2xl:text-5xl font-bold absolute left-1/2 transform -translate-x-1/2">Group List</h1>
                </div>
                {{-- contents --}}
                <div class="mx-auto h-full mt-8">
                    @foreach ($groups as $group)
                        <div class="flex items-center justify-between bg-white rounded-lg shadow p-4 mb-4 hover:bg-gray-50 transition">
                            <a href="#" class="flex items-center space-x-4 w-full">
                                @if ($group->image)
                                    <img src="{{ asset('storage/' . $group->image)}}" alt="Group Image" class="w-12 h-12 rounded-full object-cover">
                                @else
                                <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($group->name, 0, 1)) }}
                                </div>
                                @endif

                                <div class="flex flex-col">
                                    <span class="font-semibold text-lg">{{$group->name}}</span>
                                    <span class="text-lg">{{$group->members_count}}</span>
                                </div>
                            </a>
                                <div x-data="{ showEditModal: false, showDeleteModal: false }">
                                    <x-dropdown align="right" width="46">
                                        <x-slot name="trigger">
                                            <i class="fa-solid fa-ellipsis cursor-pointer text-gray-600 hover:text-black"></i>
                                        </x-slot>

                                        <x-slot name="content">
                                            <button @click="showEditModal = true" class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-100">
                                                Edit
                                            </button>
                                            <button @click="showDeleteModal = true" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                                Delete
                                            </button>
                                        </x-slot>
                                    </x-dropdown>
                        </div>
                    @endforeach
                </div>
                {{-- paginate --}}
                <div class="flex justify-center">
                </div>
            </div>
        </div>
    </div>
</div>


@section('footer')

@endsection

<!--modal-->