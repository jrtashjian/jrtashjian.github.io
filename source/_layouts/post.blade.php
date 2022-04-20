@extends('_layouts.master')

@section('body')
	<article>
		<h1>{{ $page->title }}</h1>
		<p class="mt-0">{{ $page->getDate()->format( 'F j, Y' ) }}</p>

		@yield('content')
	</article>
@endsection