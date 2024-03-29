---
title: Archives
---
@extends('_layouts.no-format')

@section('content')
	<ul>
		@foreach( $posts as $entry )
			<li class="my-1.5">
				<article class="h-entry sm:flex flex-row-reverse justify-end">
					<h2 class="my-0 font-normal">
						<a class="u-url" rel="bookmark" href="{{ $entry->getUrl() }}">{{ $entry->title }}</a>
					</h2>
					<time class="dt-published mr-3" datetime="{{ date( DATE_ATOM, $entry->date ) }}" title="Published on {{ $entry->getDate()->format( 'F j, Y' ) }}">{{ $entry->getDate()->format( 'Y-m-d' ) }}</time>
				</article>
			</li>
		@endforeach
	</ul>
@endsection