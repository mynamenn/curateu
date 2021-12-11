@extends('layouts.app')

<script>
    function usernameClick(route) {
        location.href = route;
    }
</script>

@section('content')
    <div class="container px-5 pt-10 pb-5 mx-auto">
        <div class="flex flex-wrap w-full mb-4 flex-col items-center text-center">
            <h1 class="sm:text-4xl text-3xl font-semibold title-font mb-2 text-gray-900">
                All Curators
            </h1>
            <p class="lg:w-1/2 w-full leading-relaxed text-gray-500 text-lg">
                {{ $curators->total() }} Results
            </p>
        </div>
        <div class="flex flex-wrap mb-2">
            @foreach ($curators as $curator)
                <x-curator-card :curator="$curator"></x-curator-card>
            @endforeach
        </div>
        {{ $curators->links() }}
    </div>
@endsection
