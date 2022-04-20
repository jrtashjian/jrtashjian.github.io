---
extends: _layouts.post
title: Featured Image Fallback for Child Pages
date: 2017-05-31
categories: [wordpress]
---
Continuing from the same project where I needed to [highlight a parent page in a WordPress menu](https://jrtashjian.com/2017/05/highlighting-parent-page-in-wordpress-menus/) when on a child page, featured images is another place where I wanted some custom functionality. This custom functionality I'm about to share wasn't necessarily a requirement but I wanted to make it as simple as possible for the client to edit the site easily while still maintaining the design the designer had created.

As I mentioned in the last post the site was designed to have a handful of top-level pages (parents) with an undefined number of related child pages. This site also uses the static front page option in WordPress. The site's layout was a top header navbar, then below that, a featured image with text overlaid, then the page content below that.

What I wanted to do to implement the featured image was to build in a fallback so that we always have a featured image displayed behind the overlaid text. The other option would be to have a different layout where the featured image is not displayed and the page content contains the overlaid text above the actual page content. The fallback is a bit more graceful so I decided to do that.

Below is my solution for this fallback. First, we want to see if the current page has a `post_thumbnail` (which is our featured image). If the current page returns a `post_thumbnail` we want to use that. The client should be able to specify an image for any page they want. Now if we don't find a `post_thumbnail` on the current page I want to check the topmost parent page for it. This would allow the client to set a featured image for an entire group of child pages. And finally, if a `post_thumbnail` was not returned from the topmost parent page we would then check the defined static front page and pull it from there.

More simply the fallback for the `post_thumbnail` would be: Child Page -> Parent Page -> Front Page.

```
<?php

/**
 * Generate markup for the featured image above each page.
 *
 * If a featured image does not exist for the current page we will try and pull one from the parent page. If we still
 * haven't found a featured image we will try and pull one from the 'page_on_front' (when using a static front-page).
 * Otherwise we show nothing.
 */
function featured_image( $size = 'post-thumbnail' )
{
    global $post;

    if ( !is_page() ) {
        return;
    }

    $the_post_thumbnail = get_the_post_thumbnail( $size );

    // Get post_thumbnail from top most parent if we haven't found one yet.
    if ( empty( $the_post_thumbnail ) ) {
        $page_parents   = get_post_ancestors( $post );
        $topmost_parent = array_pop( $page_parents );

        $the_post_thumbnail = get_the_post_thumbnail( $topmost_parent );
    }

    // Get post_thumbnail from "Front Page" template if we still don't find one.
    if ( empty( $the_post_thumbnail ) ) {
        $the_post_thumbnail = get_the_post_thumbnail( get_option( 'page_on_front' ), $size );
    }

    echo $the_post_thumbnail;
}
```

It's unlikely we wouldn't find a `post_thumbnail` like this but we could return a hard-coded default to account for that possibility if we didn't find one on the front page.