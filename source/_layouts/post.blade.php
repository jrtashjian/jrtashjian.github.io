@extends('_layouts.master')

@section('body')
	<article class="h-entry">
		<h1 class="p-name">{{ $page->title }}</h1>
		<time class="dt-published mr-3" datetime="{{ date( DATE_ATOM, $page->date ) }}" title="Published on {{ $page->getDate()->format( 'F j, Y' ) }}">{{ $page->getDate()->format( 'F j, Y' ) }}</time>
		<a class="p-author h-card sr-only" href="http://jrtashjian.com">JR Tashjian</a>

		<div class="e-content">
			@yield('content')
		</div>
	</article>
@endsection