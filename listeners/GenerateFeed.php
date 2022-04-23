<?php

namespace App\Listeners;

use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Item;
use TightenCo\Jigsaw\Jigsaw;

class GenerateFeed {

	public function handle( Jigsaw $jigsaw ) {
		$config = $jigsaw->getConfig();

		if ( ! $config['baseUrl'] ) {
			echo("\nTo generate a feed.xml file, please specify a 'baseUrl' in config.php.\n\n");
			return;
		}

		$feed = new Feed();

		$channel = new Channel();
		$channel
			->title( $config['siteName'] )
			->description( $config['siteDescription'] )
			->url( $config['baseUrl'] )
			->feedUrl( rtrim( $config['baseUrl'], '/' ) . '/feed.xml' )
			->language( 'en-US' )
			->copyright( 'Copyright (c) ' . $config['siteName'] . ' ' . (new \DateTime())->format('Y') )
			->pubDate( (new \DateTime())->getTimestamp() )
			->lastBuildDate( (new \DateTime())->getTimestamp() )
			->appendTo( $feed );

		$jigsaw->getCollection( 'posts' )->each( function( $post ) use ( $channel, $config ) {
			$item = new Item();
			$item
				->title( $post->title )
				->description( $post->getExcerpt() )
				->contentEncoded( $post )
				->url( $post->getUrl() )
				->author( $config['siteAuthor'] )
				->pubDate( (new \DateTime( '@' . $post->date ))->getTimestamp() )
				->guid( $post->getUrl(), true )
				->preferCdata( true )
				->appendTo( $channel );
		} );

		$jigsaw->writeOutputFile( 'feed.xml', $feed->render() );
	}
}