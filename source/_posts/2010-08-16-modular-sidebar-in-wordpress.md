---
extends: _layouts.post
title: Modular Sidebar in WordPress
date: 2010-08-16
categories: [wordpress]
---
Like all good developers, I go back and look at past code to see what I could have improved and how I can apply the new knowledge to a current project. Both of the projects in question had sidebars with sections that are shared throughout the site. However, not all sections are being displayed at once. Some blocks of HTML would be show on the homepage, another on the about page and both on the contact page. Here is how I coded it in [WordPress](http://wordpress.org) for each site.

## Site One

For this site, there is 'Live Bulletin', 'Make a Donation', 'Testimonials', 'Downloads' and 'Social Networks' sections. In the sidebar template I had each section in it's own `if` statement. For each `if` statement, I would use WordPress' [Conditional Tags](http://codex.wordpress.org/Conditional_Tags) to show or hide certain sections that I needed for a particular page. Here is a quick glance at how my template file was looking:

```
<div id="sidebar">

    <?php if ( !is_page( 270 ) AND !is_page( 262 ) ) : ?>
        <div id="live-bulletin">
            <!-- html code -->
        </div>
    <?php endif; ?>

    <?php if ( is_page( 270 ) ) : ?>
        <div id="make-donation">
            <!-- html code -->
        </div>
    <?php endif; ?>

    <?php if ( is_home() OR is_page( 262 ) ) : ?>
        <div id="testimonials">
            <!-- html code -->
        </div>
    <?php endif; ?>

    <?php if ( is_front_page() OR is_search() OR is_page( 890 ) ) : ?>
        <div id="downloads">
            <!-- html code -->
        </div>
    <?php endif; ?>

    <div id="social-networks">
        <!-- html code (shown on all pages) -->
    </div>

</div>
```

This sidebar template got pretty messy, very quickly. Little updates such as displaying a section on another page became a hassle. For the next site, I didn't rush, took my time and tried a different method.

## Site Two

I'll just use the same sections as the previous site for easy comparison. With this next method, I decided to put each section in it's own function. That way, I could separate the logic from the view easily and make modifications quickly. Here is how this template came out:

```
<?php function sidebar_livebulletin() { ?>
    <div id="live-bulletin">
        <!-- html code -->
    </div>
<?php } ?>

<?php function sidebar_makedonation() { ?>
    <div id="make-donation">
        <!-- html code -->
    </div>
<?php } ?>

<?php function sidebar_testimonials() { ?>
    <div id="testimonials">
        <!-- html code -->
    </div>
<?php } ?>

<?php function sidebar_downloads() { ?>
    <div id="downloads">
        <!-- html code -->
    </div>
<?php } ?>

<?php function sidebar_socialnetworks() { ?>
    <div id="social-networks">
        <!-- html code -->
    </div>
<?php } ?>

<div id="sidebar">
    <?php
    // Home Page
    if ( is_front_page() ) {
        sidebar_livebulletin();
        sidebar_testimonials();
        sidebar_socialnetworks();
    }

    // About Page
    if ( is_page( 8 ) ) {
        sidebar_testimonials();
        sidebar_downloads();
        sidebar_socialnetworks();
    }

    // Contact Page
    if( is_page( 12 ) ) {
        sidebar_makedonation();
        sidebar_socialnetworks();
    }
    ?>
</div>
```

Now that is a better looking template! I've seen this function-based implementation before and I really like it. Updating this template has proven to be quick and easy. I have already started to adopt this method and will continue too until I establish a better method.