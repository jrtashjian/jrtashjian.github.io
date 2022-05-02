@extends('_layouts.master')

@section('body')
	<section class="mt-0">
		<h1 class="p-name">{{ $page->title }}</h1>
		@if( $page->date )
			<time class="dt-updated mr-3" datetime="{{ date( DATE_ATOM, $page->date ) }}" title="Last updated on {{ $page->getDate()->format( 'F j, Y' ) }}">Last updated on {{ $page->getDate()->format( 'F j, Y' ) }}</time>
		@endif

		<div class="e-content">
			@yield('content')
		</div>
	</section>
@endsection