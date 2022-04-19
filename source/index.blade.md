---
extends: _layouts.master
---

Hello! My name is JR Tashjian.

I am a believer and follower of Christ. I am a challenge seeker and solve complex problems through code. I’m currently working at [GoDaddy](https://godaddy.com/) on the WordPress Experience team as a Senior Software Engineer. Before that, I’ve worked as an independent contractor as well as at [Automattic](https://automattic.com/), [Packet Tide](https://packettide.com/), [10up](https://10up.com/), and [Overit](https://overit.com/). I also contribute to the [WordPress Core](https://wordpress.org/) and [Gutenberg](https://github.com/wordpress/gutenberg) Open Source projects.

If you’d like to connect, you can reach me via [email](mailto:hello@jrtashjian.com) or on [Mastodon](https://talos.link/@jrtashjian).

<ul>
	@foreach( $posts as $entry )
	<li class="my-3">
		<a href="{{ $entry->getUrl() }}">{{ $entry->title }}</a>
		<span class="block">{{ $entry->getDate()->format( 'F j, Y' ) }}</span>
	</li>
	@endforeach
</ul>