@extends('layouts.app')

@section('content')
    <script>
        function usernameClick(route) {
            location.href = route;
        }

        function redirectToLink(link) {
            window.open(link);
        }
    </script>

    <div>
        <div class="flex-column pt-10 pb-6 px-4 rounded-lg overflow-hidden align items-center justify-center text-center">
            <img class="w-24 h-24 mb-4 inline-flex items-center rounded-full bg-indigo-100 text-indigo-500 flex-shrink-0"
                src={{ $collection->photo }} alt="content">

            <h1 class="title-font text-2xl font-semibold text-gray-900 mb-1">
                {{ $collection->name }}
            </h1>
            <h2 class="tracking-widest text-base title-font font-medium text-gray-500 mb-1">{{ $collection->description }}
            </h2>
            <h2 onclick="usernameClick('{{ route('user.show', ['username' => $collection->user->username]) }}')"
                class="block tracking-widest text-base title-font font-medium text-gray-500 mb-4 cursor-pointer transition duration-200 ease-in-out hover:text-indigo-600 hover:underline">
                {{ '@' }}{{ $collection->user->username }}
            </h2>

            {{-- Tags --}}
            <div class="flex items-center justify-center mb-4">
                @foreach ($collection->tags->take(3) as $index => $tag)
                    <div class="border-2 border-gray-200 mr-2 px-1 rounded-sm bg-gray-50">
                        <p class="leading-relaxed text-xs text-gray-600 ">{{ Str::upper($tag->name) }}</p>
                    </div>
                @endforeach
                @if ($collection->tags->count() > 3)
                    <p class="leading-relaxed text-xs text-gray-600 ">+ {{ $collection->tags->count() - 3 }}</p>
                @endif
            </div>

            {{-- Upvote --}}
            @if (!auth()->user() || !$collection->likedBy(auth()->user()))
                <form action="{{ route('collections.likes', $collection->id) }}" method="post">
                    @csrf
                    <button type="submit"
                        class="flex items-center justify-center bg-indigo-500 px-8 py-3 rounded-md m-auto transition duration-200 ease-in-out hover:bg-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-chevron-up mr-1">
                            <polyline points="18 15 12 9 6 15"></polyline>
                        </svg>
                        <p class="text-sm font-semibold text-white mr-2">UPVOTE</p>
                        <p class="text-sm font-extrabold text-white">{{ $collection->likes->count() }}</p>
                    </button>
                </form>
            @else
                <form action="{{ route('collections.likes', $collection->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center justify-center border-2 border-indigo-500 bg-white px-8 py-3 rounded-md m-auto transition duration-200 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#3F51B5" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-chevron-up mr-1">
                            <polyline points="18 15 12 9 6 15"></polyline>
                        </svg>
                        <p class="text-sm font-semibold text-indigo-500 mr-2">UPVOTED</p>
                        <p class="text-sm font-extrabold text-indigo-500">{{ $collection->likes->count() }}</p>
                    </button>
                </form>
            @endif
        </div>

        <p class="sm:mx-8 mx-2 font-semibold text-2xl">{{ $items->total() }}
            {{ Str::plural('Item', $items->total()) }}</p>

        <div class="sm:mx-8 mt-4 mb-6 mx-2 border-2 border-gray-300 border-opacity-50 rounded-md">
            @foreach ($items as $index => $item)
                <div class="transition duration-500 ease-in-out hover:bg-gray-100 transform hover:-translate-y-1 flex px-4 py-5 sm:flex-row flex-row cursor-pointer"
                    onclick="redirectToLink('{{ $item->link }}')">
                    <img class="sm:mb-0 sm:w-20 sm:h-20 w-16 h-16 mr-8 mb-4 inline-flex items-center justify-center bg-indigo-100 text-indigo-500 flex-shrink-0"
                        src={{ $item->photo }} alt="content">
                    <div class="flex-grow">
                        <h2 class="text-gray-900 text-lg title-font font-medium">{{ $item->name }}</h2>
                        <p class="leading-relaxed text-base mb-1">
                            {{ $item->description }}
                        </p>
                        <div class="flex flex-row items-center">
                            {{-- <svg viewBox="0 0 13 13" xmlns="http://www.w3.org/2000/svg" color="light-gray" width="12"
                                height="18" class="mr-2">
                                <path
                                    d="M6.5.75c-3.31 0-6 2.362-6 5.267 0 2.905 2.69 5.266 6 5.266a6.8 6.8 0 001.036-.08l2.725 1.486a.5.5 0 00.74-.44V9.46a4.893 4.893 0 001.5-3.443C12.5 3.112 9.81.75 6.5.75z"
                                    fill="currentColor"></path>
                            </svg> --}}
                            <p class="mr-3 text-gray-500">{{ $item->created_at->diffForHumans() }}</p>
                        </div>

                    </div>

                    <x-upvote-button :object="$item" :actionPath="route('items.likes', $item->id)"></x-upvote-button>
                </div>
                {{-- If no pages and is at last element, don't show hr --}}
                @if ($items->hasPages() == false && $index == $items->count() - 1)
                    {{-- Do nothing --}}
                @else
                    <hr class="border-t-2 border-gray-300 border-opacity-50" />
                @endif
            @endforeach

            @if ($items->hasPages())
                <div class="py-3 px-4">
                    {{ $items->links() }}
                </div>
            @endif
        </div>

        <x-comments :user="$collection->user" :comments="$comments" :collection="$collection"></x-comments>
    </div>
@endsection