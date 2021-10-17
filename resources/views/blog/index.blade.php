@extends('layouts.app')


@section('content')
    <div class="w-4/5 m-auto text-center ">
        <div class="py-15 border-b border-gray-200">
            <h1 class="text-6xl">
                Blog Posts
            </h1>
        </div>
    </div>

    @if(session()->has('message'))
        <div class="m-auto mt-10 pl-2 w-4/5">
            <p class="w-2/6 text-gray-50 bg-reen-500 rounded-2xl py-4">
                 {{ session()->get('message') }}
            </p>
        </div>

    @endif

    @if (Auth::check())
        <div class="pt-15 w-4/5 m-auto">
            <a href="/blog/create" class="bg-blue-500 uppercase bg-transparent text-gray-100 text-xs font-extrabold py-3 px-5 rounded-3xl">
            Create Post</a>
        </div>
    @endif

    @foreach($posts as $post)

        <div class="sm:grid grid-cols-2 w-4/5 gap-20 mx-auto py-15 border-b">
            <div>
                <div>
                    <img src="{{ asset('images/'. $post->image_path )}}" width="700" class="object-contain" alt="">
                </div>
            </div>
            <div>
                <h2 class="text-gray-700 font-bold text-5xl p-4 ml-4">
                   {{ $post->title }} {{-- Learn how to write Laravel code --}}
                </h2>

                <span class="text-gray-500 p-4 ml-5">
                    By <span class="font-bold italic text-gray-800 ">{{ $post->user->name }}{{--Ogundele Babatunde Stephen--}}
                    </span>, Created on {{ date('jS M Y', strtotime($post->updated_at)) }} {{--1 day ago --}}
                </span>

                <p class="text-xl text-gray-700 p-10 leading-8 font-light">
                    
                    {{ $post->description }}
                </p>

                <a href="/blog/{{ $post->slug }}" class="uppercase bg-blue-500 text-gray-100 text-lg font-extrabold m-10 py-5 px-8 rounded-3xl">
                    Keep reading
                </a>

                {{-- checks if user is logged in and if the user_id of the person is equal to the creator of the post --}}
                @if(isset(Auth::user()->id) && Auth::user()->id == $post->user_id)
                    <span class="float-right">
                        <a href="/blog/{{ $post->slug }}/edit" class="text-gray-700 italic hover:text-gray-900 pb-1 border-b-2">
                        Edit
                        </a>
                    </span>

                    <span class="float-right">
                        <form action="/blog/{{ $post->slug }}" method="POST">
                            @csrf
                            @method('delete')

                            <button class="text-red-500 pr-3" type="submit">
                                Delete
                            </button>
                        </form>
                    </span>
                @endif
            </div>
        </div>

    @endforeach

@endsection