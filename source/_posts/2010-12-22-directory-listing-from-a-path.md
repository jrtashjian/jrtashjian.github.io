---
extends: _layouts.post
title: Directory Listing from a Path
date: 2010-12-22
categories: [code]
---
```
$path = dirname( _FILE_ );

$listing = array_filter( scandir( $path ), function( $var ) {

	// remove special directories '.' and '..' from listing
	if ( preg_match( '/^\[.\]{1,2}$/', $var ) ) { return FALSE; }

	// remove files from listing
	if ( !is_dir( $var ) ) { return FALSE; }

	return TRUE;
} );
```

This is a little snippet of code I've been using a lot recently. This function will return all directories inside the path passed as `$path`. The functions utilizes the ability of [Anonymous Functions](http://php.net/functions.anonymous), only available in PHP 5.3.0.