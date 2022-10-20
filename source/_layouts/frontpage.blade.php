@extends('_layouts.master')

@section('body')
	<figure style="background-image:url(/assets/images/about-page-pixel.webp);">
		<img src="/assets/images/about-page.webp" height="400" width="720" alt="Portrait of JR Tashjian" class="image-full" loading="lazy" />
	</figure>

	@yield('content')

	<section>
		<ul>
			<li><a href="/archive">/Archive</a></li>
			<li><a href="/uses">/Uses</a></li>
			<li><a href="/now">/Now</a></li>
		</ul>
	</section>
@endsection