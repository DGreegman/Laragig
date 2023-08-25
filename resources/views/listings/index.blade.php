<x-layout>
{{-- <h1> {{ $header }} <h1> --}}
@include('partials._hero')
@include('partials._search')
<div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">


@if (count($listings) == 0)
    <p>No Listing Found</p>
@endif

@foreach ($listings as $listing)

<x-listing-card :listing="$listing" />

{{-- <a href="/listing/{{$listing['id']}}">{{ $listing->title }}</a>
    <p>{{ $listing['description'] }}</p> --}}
    
@endforeach
</div>

<div class="mt-6 p-4">
    {{$listings->links()}}
</div>
</x-layout>

