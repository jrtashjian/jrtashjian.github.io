---
extends: _layouts.post
title: CodeIgniter’s alternator() function
date: 2009-10-07
categories: [codeigniter]
---
It's surprising to me how often I find little functions for tedious tasks, that [CodeIgniter](http://codeigniter.com) already has built in. One of these functions is the `alternator()` function in the [String Helper](http://codeigniter.com/user_guide/helpers/string_helper.html). To begin using this function, make sure you have loaded the String Helper with the following code:

```
$this->load->helper( 'string' );
```

What the `alternator()` function does is allow two or more items to be alternated between when iterating through a loop. Example from the CodeIgniter User Guide:

```
for ( $i = 0; $i < 10; $i++ ) {
    echo alternator( 'string one', 'string two' );
}
```

There is also no limit to how many parameters you can have:

```
echo alternator( 'one', 'two', 'three', 'four', 'five' );
```

## Put it to Use

What would you ever need that for? Well, what about if you are creating a list of items and every other needs `class="alt"` attached to it for styling differences? I run into this issue all the time. This is how I used to do it:

```
<ul>
    <?php $count = 1; ?>
    <?php foreach ( $list as $item ) : ?>
        <?php ( empty( $count ) ) ? $count = 1 : $count = 0; ?>
        <li <?php echo ( $count == 1 ) ? 'class="alt"' : ''; ?>>
            <?php echo $item; ?>
        </li>
    <?php endforeach; ?>
</ul>
```

And this is with the `alternator()` function:

```
<ul>
    <?php foreach ( $list as $item ) : ?>
        <li <?php echo alternator( 'class="alt"', '' ); ?>>
            <?php echo $item; ?>
        </li>
    <?php endforeach; ?>
</ul>
```

The `alternator()` function makes the ability to do this, much easier and cleaner than my original way. Hopefully I've helped someone out who had no idea this function was available.