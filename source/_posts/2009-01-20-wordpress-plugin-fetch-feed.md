---
extends: _layouts.post
title: WordPress 'Fetch Feed' Plugin
date: 2009-01-20
---
'Fetch Feed' is a RSS retrieval and caching plugin for [WordPress](https://wordpress.org). You can use it to parse any RSS feed for displaying on your site. The beauty of this plugin is that you are not limited to how you display your feed. All you need to know is a basic understanding of PHP [foreach](https://php.net/foreach) loops and array/object structure.

## Installation
1. [Download the file](https://wordpress.org/extend/plugins/fetch-feed) and unzip it. Upload the 'fetch-feed' folder to your `~/wp-content/plugins/` directory
2. Make sure the `~/wp-content/plugins/fetch-feed/cache/` directory is writeable
3. Activate the plugin through the 'Plugins' admin menu in WordPress

## Usage
To begin using the function we need to pass the url to the RSS feed we would like to work with and save the result in a variable. I will be using `$xml`. We will also specify in the second parameter, the amount in minutes we would like the feed cached for:

```
// Retrieve RSS from https://jrtashjian.com and cache for 60 minutes
$xml = fetch_feed( "https://jrtashjian.com/feed/", 60 );
```

After the function retrieves the RSS you can loop through the object using a foreach loop, like so:

```
<h2><?php echo $xml->channel->title; ?></h2>
<ul>
    <?php foreach ( $xml->channel->item as $item ) : ?>
        <li><a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a></li>
    <?php endforeach; ?>
</ul>
```