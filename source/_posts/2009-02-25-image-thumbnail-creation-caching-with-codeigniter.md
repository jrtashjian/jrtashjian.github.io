---
extends: _layouts.post
title: Image Thumbnail Creation & Caching With CodeIgniter
date: 2009-02-25
categories: [codeigniter]
---
How you handle the saving and display of images is very important while planning your web app. Questions you would ask yourself include: How should images be stored? What will the standard naming conventions be? How will thumbnails be created? In this article, I will be focusing on how I decided the way thumbnails would be generated in my applications.

## Initial Plan

When I first started planning how images would be handled in a recent app, things went quite smoothly. I planned the logic flow like this:

1. User uploads image
2. PHP saves image in `/assets/images/` directory
3. PHP would loop through an array of pre-defined image dimensions and create image thumbnails from the image
4. To display thumbnail, pass the path to thumbnail in image `src` attribute.

Not so hard right? WRONG!

## The Problem

In one of my config files, I created an array which you could specify multiple image dimensions (height and width) for use throughout the website/application. This works BUT, what if the person coding the front-end needs different dimensions for an already uploaded image? Or, maybe the client requires a small sizing change to the thumbnail?

Looping through the array sounded like a good idea but, for small changes like these requires more work than needed. If I continued using the array method to update or add a new thumbnail dimension, every previously uploaded image would need to be updated with the new thumbnail. There has to be a better way.

## The Solution

With the task at hand, a [friend](http://mondaybynoon.com) of mine lead me to a PHP library called [phpThumb()](http://phpthumb.sourceforge.net/). phpThumb() uses the GD library to create thumbnails from images on the fly. This is exactly what I needed. A way to dynamically create image thumbnails rather than pre-defined dimensions. phpThumb() was the way to go but, I didn't want to just use the library, I wanted to understand it. What better way to understand a library than to create one yourself?

## The Function

By nature, this function will be a helper function in [CodeIgniter](http://codeigniter.com/). The only library being used will be the [Image Library](http://codeigniter.com/user_guide/libraries/image_lib.html).

To begin creating the function, we need to create a file in our `./application/helpers/` directory. I will be calling this file `image_helper.php`. Now we can create our function.

To generate a thumbnail of an image on the fly, we will have to pass 3 parameters to our function: The image path, the specified height, and the specified width. I will be naming the function `image_thumb`. You can use any name you wish.

```
<?php

function image_thumb( $image_path, $height, $width ) {
    // code
}

/* End of file image_helper.php */
/* Location: ./application/helpers/image_helper.php */
```

In order to use the Image Library of CodeIgniter, we need the CodeIgniter object available to us. In the current state of the function, if we used `$this->load->library('image_lib')` to load the Image Library, the function would throw error. This is because helpers in CodeIgniter are not classes but rather a collection of functions. So, how do we use CodeIgniter's libraries in our helper? CodeIgniter's got us covered! CodeIgniter has a function called `get_instance()` which returns the CodeIgniter super object. We will use it like so:

```
function image_thumb( $image_path, $height, $width ) {
    // Get the CodeIgniter super object
    $CI =& get_instance();
}
```

It is important to be aware that we are passing the `get_instance()` function by reference. Passing by reference allows us to use the original CodeIgniter object (already in memory) rather than creating a copy of it (duplicating memory). Passing variables and functions by reference comes in handy when optimizing your PHP application. But, that is another article for another day.

The next thing we must do is figure out where to store the generated thumbnails. This is for caching purposes. When the function is used, the original image path is passed to it. To get the directory path from the image path we will use PHP's built-in `dirname()` function.

For example: I would pass `assets/images/picture-1/picture-1.jpg` as the original image file path and `dirname('assets/images/picture-1/picture-1.jpg')` would return `assets/images/picture-1/` as the directory to save the generated thumbnails. Notice I will not pass the path with a beginning slash (/).

We also need to construct the filename we want for our thumbnail image. I will make the filename `height_width.jpg` because all of my images are grouped inside their own folders. Take a look at the updated function:

```
function image_thumb( $image_path, $height, $width ) {
    // Get the CodeIgniter super object
    $CI =& get_instance();

    // Path to image thumbnail
    $image_thumb = dirname( $image_path ) . '/' . $height . '_' . $width . '.jpg';
}
```

Now, the way caching works in this function is if the image thumbnail path (`$image_thumb`) exists, the thumbnail has already been created therefore does not need to be generated again. If the file does not exists, we need to create it!

To create the image thumbnail, we need to load CodeIgniter's Image Library.

```
function image_thumb( $image_path, $height, $width ) {
    // Get the CodeIgniter super object
    $CI =& get_instance();

    // Path to image thumbnail
    $image_thumb = dirname( $image_path ) . '/' . $height . '_' . $width . '.jpg';

    if ( !file_exists( $image_thumb ) ) {
        // LOAD LIBRARY
        $CI->load->library( 'image_lib' );
    }
}
```

Before we can do anything with the Image Library, we need to configure it.

```
function image_thumb( $image_path, $height, $width ) {
    // Get the CodeIgniter super object
    $CI =& get_instance();

    // Path to image thumbnail
    $image_thumb = dirname( $image_path ) . '/' . $height . '_' . $width . '.jpg';

    if ( !file_exists( $image_thumb ) )
    {
        // LOAD LIBRARY
        $CI->load->library( 'image_lib' );

        // CONFIGURE IMAGE LIBRARY
        $config['image_library']    = 'gd2';
        $config['source_image']     = $image_path;
        $config['new_image']        = $image_thumb;
        $config['maintain_ratio']   = TRUE;
        $config['height']           = $height;
        $config['width']            = $width;
        $CI->image_lib->initialize( $config );
    }
}
```

I am using GD2 as the image library. You can use what you wish, gd2 is default. The source_image config variable is the path to the original (full-size) image. The new_image config variable is the path to our specified thumbnail image. CodeIgniter will save the thumbnail to this path. I have set maintain_ratio to TRUE so that CodeIgniter automatically resizes our image to the best dimensions to fit our specified height and width, without distorting the image in any way.

Finally, we initialize our configuration:

```
$CI->image_lib->initialize( $config );
```

After configuration is complete, we need to process the image to generate our thumbnail. Do this by running `$CI->image_lib->resize()`. After resizing the image, we need to reset all values used by the Image Library when processing an image. Do this by running `$CI->image_lib->clear()`.

Last but not least, we need to return our thumbnail! In my function, I have returned the HTML image tag (`img`) with the thumbnail path already in the source (`src`) attribute. You can just return the path if you want, just return the generated src path.

## The Complete Function

```
<?php

function image_thumb( $image_path, $height, $width ) {
    // Get the CodeIgniter super object
    $CI =& get_instance();

    // Path to image thumbnail
    $image_thumb = dirname( $image_path ) . '/' . $height . '_' . $width . '.jpg';

    if ( !file_exists( $image_thumb ) ) {
        // LOAD LIBRARY
        $CI->load->library( 'image_lib' );

        // CONFIGURE IMAGE LIBRARY
        $config['image_library']    = 'gd2';
        $config['source_image']     = $image_path;
        $config['new_image']        = $image_thumb;
        $config['maintain_ratio']   = TRUE;
        $config['height']           = $height;
        $config['width']            = $width;
        $CI->image_lib->initialize( $config );
        $CI->image_lib->resize();
        $CI->image_lib->clear();
    }

    return '<img src="' . dirname( $_SERVER['SCRIPT_NAME'] ) . '/' . $image_thumb . '" />';
}

/* End of file image_helper.php */
/* Location: ./application/helpers/image_helper.php */
```

## Using the Function

In order to use the function, you will need to load the helper, either in your controller or the application autoload file.

General usage in View:

```
<?php echo image_thumb( 'assets/images/picture-1/picture-1.jpg', 50, 50 ); ?>
```