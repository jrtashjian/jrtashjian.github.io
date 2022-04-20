@extends('_layouts.master')

@section('body')
	<figure>
		<img src="/assets/images/about-page.jpg" class="image-full" loading="lazy" />
		<img src="/assets/images/about-page-pixel.jpg" />
	</figure>

	@yield('content')

	@include('_partials.all-posts')
@endsection