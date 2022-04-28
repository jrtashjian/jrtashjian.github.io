@extends('_layouts.master')

@section('body')
	<figure style="background-image:url(/assets/images/about-page-pixel.webp);">
		<img src="/assets/images/about-page.webp" height="400" width="720" alt="Portrait of JR Tashjian" class="image-full" loading="lazy" />
	</figure>

	@yield('content')

	@include('_partials.all-posts')
@endsection