@extends('_layouts.master')

@section('body')
	<article class="h-entry">
		<header>
			<h1 class="p-name">{{ $page->title }}</h1>
			<time class="dt-published mr-3" datetime="{{ date( DATE_ATOM, $page->date ) }}" title="Published on {{ $page->getDate()->format( 'F j, Y' ) }}">{{ $page->getDate()->format( 'F j, Y' ) }}</time>
			<span class="p-author h-card sr-only" href="http://jrtashjian.com">JR Tashjian</span>
		</header>

		@if( $page->alert_message )
			<div class="alert alert--{{ $page->alert_type }}" role="alert">
				{!! \Michelf\Markdown::defaultTransform( $page->alert_message ) !!}
			</div>
		@endif

		<div class="e-content">
			@yield('content')
		</div>
	</article>
@endsection