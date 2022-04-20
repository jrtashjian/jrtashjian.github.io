---
extends: _layouts.post
title: Highlighting parent page in WordPress menus
date: 2017-05-26
categories: [wordpress]
---
Menus in WordPress are a really simple and easy way to manage various navigation bars in a theme. `wp_nav_menu()` does a lot of the work automatically and includes a number of CSS classes for styling. Highlighting the current page is especially useful using `.current-menu-item` and `.current-menu-ancestor` classes.

There was one issue I recently ran into though. In order for the top-level page to be highlighted when a sub-page has been selected, you must have the sub-pages added into the navigation. This makes sense but for a recent project, I wanted to make it easy for the client to add sub-pages to the site without having to also add the pages to the WordPress navigation menu for parent-menu highlighting. I wanted top-level pages to have the .current-menu-ancestor to be added regardless if it was in a sub-menu.

Below is a solution to this problem. The way I set up my project was a handful of parent pages with an undefined number of child pages. I then added the parent pages to the Menu. We didn't want a dropdown menu.

Here we add a filter to the classes generated during the generation of the menu. First, we check if we're on a page with `is_page()`, if not we don't filter anything. If we are on a page we access the current post from the global `$post` variable and look for the topmost parent, if we don't find one the current post is not a child and we don't filter anything.

If we're on a page and we found a topmost parent we then check if the current menu `$item` being generated matches the topmost parent we found. If so we add the `.current-menu-ancestor` CSS class.

```
<?php

/**
 * Add the 'current-menu-ancestor' class to a parent page menu item where the child page is not part of the menu.
 *
 * @param array   $classes The CSS classes that are applied to the menu item's <li> element
 * @param WP_Post $item    The current menu item
 *
 * @return array The filtered CSS classes.
 */
function active_parent_in_menu_for_page( $classes, $item )
{
    global $post;

    if ( !is_page() ) {
        return $classes;
    }

    $page_parents   = get_post_ancestors( $post );
    $topmost_parent = array_pop( $page_parents );

    if ( empty( $topmost_parent ) ) {
        return $classes;
    }

    if ( $topmost_parent == $item->object_id ) {
        array_push( $classes, 'current-menu-ancestor' );
    }

    return $classes;
}

add_filter( 'nav_menu_css_class', 'active_parent_in_menu_for_page', 10, 2 );
```

Such a simple solution which makes the editing experience easier for the client when working with a custom theme.