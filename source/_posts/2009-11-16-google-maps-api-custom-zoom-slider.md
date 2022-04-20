---
extends: _layouts.post
title: "Google Maps API: Custom Zoom Slider"
date: 2009-11-16
categories: [code]
---
[Google Maps API](http://code.google.com/apis/maps/) is just one of the API's I've been working with recently. The most recent problem I've run into that needed to be solved was the ability to create a custom designed zoom slider, replacing the default. The default one was not gonna work for this project and the designer created a better looking one.

While searching online to see if this had already been achieved. I failed to find any example of what I needed to accomplish easily and still be able to reuse the code. So, I decided to take on the feat. But, where to start?

## jQuery

I am a huge fan of [jQuery](http://jquery.com) and jQuery has a user interface library, [jQuery UI](http://jqueryui.com). jQuery UI gives us the ability to create a slider widget, which is highly customizable and would work for what I needed to do.

So, go download [jQuery](http://jquery.com) and download [jQuery UI](http://jqueryui.com/download) and we'll get started! You will also need to get a [Google Maps API key](http://code.google.com/apis/maps/signup.html).

## Setup

To begin, we will have to create an HTML file which loads all of our stylesheets, javascript and google map. Below is the initial HTML code. I have added the HTML for our custom slider already.

```
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us" lang="en-us">
<head profile="http://gmpg.org/xfn/11">

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Google Maps API: Custom Zoom Slider</title>

    <!-- JQUERY -->
    <script type="text/javascript" charset="utf-8" src="javascript/jquery.js"></script>
    <script type="text/javascript" charset="utf-8" src="javascript/jquery.ui.js"></script>

    <!-- GOOGLE MAPS API -->
    <script src="http://maps.google.com/maps?file=api&v=2&sensor=false&key=YOUR_API_KEY" type="text/javascript"></script>

</head>
<body>

<!-- our custom slider container -->
<div id="zoom-slider">

    <!-- decrease zoom level control -->
    <a id="zoom-control-minus" href="#"></a>

    <!-- zoom slider control -->
    <div id="zoom-range">
        <div id="zoom-path"></div>
    </div>

    <!-- increase zoom level control -->
    <a id="zoom-control-plus" href="#"></a>

</div>

<!-- this is where google maps will load -->
<div id="map"></div>

</body>
</html>
```

## Loading Google Maps API

Next we will need to load the Google Maps API. Here is the code. It is commented pretty well and should be easy to understand.

```
// store the current zoom level for reference with google maps and custom zoom slider
var currentZoomLevel = 10;

// map object (global)
var map;

$(document).ready(function() {
    // create the google map
    var map = new GMap2(document.getElementById("map"));

    // set starting center point
    map.setCenter(new GLatLng(37.4419, -122.1419), currentZoomLevel);

    // force normal maps type
    map.setMapType(G_NORMAL_MAP);

    // define minimum and maximum zoom levels
    G_NORMAL_MAP.getMinimumResolution = function() { return 0; }
    G_NORMAL_MAP.getMaximumResolution = function() { return 19; }

    // sets the map to "animate" zoom (only on double click and mouse wheel scroll)
    map.enableContinuousZoom();

    // enable the ability to zoom via mouse wheel
    map.enableScrollWheelZoom();
});
```

You can configure the return values of `G_NORMAL_MAP.getMinimumResolution` and `G_NORMAL_MAP.getMaximumResolution` to limit the zoom distance. 0 is the lowest value and 19 being the highest value (20 zoom levels).

## Lets Style It!

Now that we have Google Maps loading into our div, we need to style it! Below is the code to style the Google Map and the custom slider. Again, I use a reset stylesheet which is bundled in the download.

```
/* Globals
------------------------------------------------------*/
body { font:75% helvetica, arial, sans-serif; background:#fff; color:#333; text-align:center; }

/* Google Map
------------------------------------------------------*/
#map { margin-top:25px; height:300px; width:518px; border:1px solid #fff; }

/* Custom Slider
------------------------------------------------------*/
#zoom-slider { margin:0 auto; position:relative; height:28px; width:203px; background:url(../images/zoom-slider-background.jpg) no-repeat; }
/* minus button */
#zoom-slider #zoom-control-minus { position:absolute; top:9px; left:11px; display:block; height:10px; width:10px; background:url(../images/zoom-control-minus.jpg) no-repeat; overflow:hidden; text-indent:-9999em; }
/* plus button */
#zoom-slider #zoom-control-plus { position:absolute; top:9px; right:11px; display:block; height:10px; width:10px; background:url(../images/zoom-control-plus.jpg) no-repeat; overflow:hidden; text-indent:-9999em; }
/* container for the zoom 'handle' */
#zoom-slider #zoom-range { position:absolute; top:9px; left:31px; height:10px; width:140px; background:url(../images/zoom-slider-range.jpg) no-repeat; z-index:1; }
/* the zoom handle */
#zoom-slider #zoom-range .ui-slider-handle { position:absolute; top:0px; margin-left:-5px; height:10px; width:10px; background:url(../images/zoom-control-handle.png) no-repeat; border:0; cursor:pointer; z-index:2; outline:none; }
/* size path handle can travel*/
#zoom-slider #zoom-range #zoom-path { position:absolute; height:10px; width:130px; top:0px; left:5px; cursor:pointer; }
```

The zoom slider will now look like this (images bundled in the download):

![](/assets/images/zoom-slider-image.jpg "zoom-slider-image")

## Make It Work

Now that we have everything looking nice and loading correctly, we need to make it work! Using jQuery UI and the Slider Widget, we will hook the `div#zoom-slider div#zoom-path` and allow jQuery UI to do it's magic. however, we still need to hook the plus and minus buttons and make the slider communicate with Google Maps to update our map. In the Javascript, after we are done setting up Google Maps, we will need to place this code.

```
$(function() {
    // slider target
    var target = $('#zoom-slider #zoom-path');

    // create the slider
    target.slider({
        orientation: 'horizontal',
        value: currentZoomLevel,
        min: parseInt(G_NORMAL_MAP.getMinimumResolution()),
        max: parseInt(G_NORMAL_MAP.getMaximumResolution()),
        step: 1,
        animate: true,
        stop: function() {
            map.setZoom(parseInt(target.slider('option','value')));
        }
    });

    // update slider on zoom with double click
    GEvent.addListener(map, 'moveend', function() { target.slider('option','value', map.getZoom()); });

    // maximum slider value
    var maxValue = parseInt(target.slider('option', 'max'));

    // minimum slider value
    var minValue = parseInt(target.slider('option', 'min'));

    // hook increase zoom control
    $('#zoom-control-plus').click(function() {
        // current slider value
        var currentValue = parseInt(target.slider('option','value'));

        // current slider value increased by 1
        var newValue = currentValue+1;

        // is new value greater than max value?
        if(newValue = minValue) {
            // increase slider value
            target.slider('option', 'value', newValue);
            map.setZoom(newValue);
        } else {
            // slider is at max value
            target.slider('option', 'value', minValue);
            map.setZoom(minValue);
        }
        return false;
    });
});
```

Slider does a nice job of incrementing and decrementing the value and moving the handle the appropriate distance. Anytime the slider moves we need to update the map. Anytime the map moves we need to update the slider.

So, when initializing the slider, we hook the stop event of the slider. Each time the slider "stops" movement, we update the map zoom using the Google Map API function [`setZoom()`](http://code.google.com/apis/maps/documentation/reference.html#GMap2.setZoom). So, now we know when we move the slider, the map will update as well!

Next, we hook the `moveend` event of Google Maps. If the user double clicks the map to zoom in and out or uses the scroll wheel to zoom in and out, we update the zoom value of the slider using `$.slider('option', 'value', map.getZoom())`. So, now we know when the map moves the slider will move as well.

Next, we need to hook the plus and minus buttons to allow a user to click them to zoom in and out. Using jQuery, we will bind each click event to update the map and slider values. The plus button will continue to zoom in until we reach the maximum zoom value. The minus button will continue to zoom out until we reach the minimum zoom value.