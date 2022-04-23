<?php

namespace App\Listeners;

use TightenCo\Jigsaw\Jigsaw;

class GenerateIndex {

	public function handle( Jigsaw $jigsaw ) {
		$data = collect( $jigsaw->getCollection( 'posts' )->map( function( $page ) use ( $jigsaw ) {
			return [
				'title' => $page->title,
				'link' => rightTrimPath( $jigsaw->getConfig( 'baseUrl' ) ) . $page->getPath(),
				'snippet' => $page->getExcerpt(),
			];
		} )->values() );

		$jigsaw->writeOutputFile( 'index.json', json_encode( $data ) );
	}
}