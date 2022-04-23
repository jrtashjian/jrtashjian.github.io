@extends('_layouts.master')

@section('body')
	<figure>
		<img src="/assets/images/about-page.webp" height="400" width="720" alt="Portrait of JR Tashjian" class="image-full" loading="lazy" />
		<img src="/assets/images/about-page-pixel.webp" height="400" width="720" alt="Pixelated portrait of JR Tashjian" />
	</figure>

	@yield('content')

	@include('_partials.all-posts')
@endsection