@if( count( $posts ) > 0 )
	<section>
		<h2>Archive</h2>
		<ul>
			@foreach( $posts as $entry )
				<li class="my-3">
					<a href="{{ $entry->getUrl() }}">{{ $entry->title }}</a>
					<span class="block">{{ $entry->getDate()->format( 'Y-m-d' ) }}</span>
				</li>
			@endforeach
		</ul>
	</section>
@endif