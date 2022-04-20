---
extends: _layouts.post
title: "Google Maps API: Center Point Location"
date: 2009-11-23
categories: [code]
---
As stated in the [last article](https://jrtashjian.com/2009/11/google-maps-api-custom-zoom-slider/ "Google Maps API: Custom Zoom Slider"), I have been working a lot with the [Google Maps API](http://code.google.com/apis/maps/) for a current client project. The most recent problem, was the application was required to display a quick overview of the current location the user was looking at. The City and the State.

Again, [jQuery](http://jquery.com/) will be used in this demo and we will be using the [GClientGeocoder](http://code.google.com/apis/maps/documentation/javascript/v2/reference.html#GClientGeocoder) service.

## Loading Google Maps API

Here is the HTML for the map and the initial Google Maps API javascript code:

```
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-us" lang="en-us">
<head profile="http://gmpg.org/xfn/11">

    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Google Maps API: Center Point Location</title>

    <!-- JQUERY -->
    <script type="text/javascript" charset="utf-8" src="javascript/jquery.js"></script>
    <script type="text/javascript" charset="utf-8" src="javascript/jquery.ui.js"></script>

    <!-- GOOGLE MAPS API -->
    <script src="http://maps.google.com/maps?file=api&v=2&sensor=false&key=YOUR_API_KEY_HERE" type="text/javascript"></script>

    <script type="text/javascript">
    // store the current zoom level for reference with google maps and custom zoom slider
    var currentZoomLevel = 10;

    // map object (global)
    var map;

    // geocoder object (global)
    var geocoder = new GClientGeocoder();

    $(document).ready(function() {
        // create the google map
        map = new GMap2(document.getElementById("map"));

        // set starting center point
        map.setCenter(new GLatLng(37.4419, -122.1419), currentZoomLevel);

        // set map UI as default
        map.setUIToDefault();
    });
    </script>
</head>
<body>

<div id="container">
    <h1>Google Maps API: Center Point Location</h1>

    <div id="content">
        <ul>
            <li><strong>State: </strong><span id="map-state">current state</span></li>
            <li><strong>City: </strong><span id="map-city">current city</span></li>
        </ul>

        <div id="map"></div>
    </div>
</div>

</body>
</html>
```

## Retrieving the Current Location

Now that Google Maps API has been loaded, we need to create a function to get the current center-point's Longitude and Latitude. Then we need to get the address, via GClientGeocoder. Then we need to strip out the data we need and update our display.

```
function updateMapLocation() {
    geocoder.getLocations(map.getCenter().toString(), function(point) {

        var currentAddress = point.Placemark[0].address;
        currentAddress = $.trim(currentAddress);
        var currentAddressMatches = currentAddress.split(/^([^,]\*),s([^,]\*),s([A-Z]{2})s([0-9]\*),s([^,]\*)$/);

        if(/^([^,]\*),s([a-zA-Z^,]\*)[0-9s]\*,s[^,]\*$/.test(currentAddress)) {
            var currentAddressMatches = currentAddress.split(/^([^,]\*),s([a-zA-Z^,]\*)[0-9s]\*,s[^,]\*$/);
        } else if(/^[^,]\*,s([^,]\*),s([a-zA-Z^,]\*)[0-9s]\*,s[^,]\*$/.test(currentAddress)) {
            var currentAddressMatches = currentAddress.split(/^[^,]\*,s([^,]\*),s([a-zA-Z^,]\*)[0-9s]\*,s[^,]\*$/);
        }

        var currentCity = currentAddressMatches[1];
        var currentState = currentAddressMatches[2];

        $('span#map-state').html(currentState);
        $('span#map-city').html(currentCity);
    });
}
```

In the code above, I realized GClientGeocoder was returning addresses for the current center point, in a few different formats. The two regular expression tests determine which format was returned and then splits out the data we need. After the data has been retrieved, we update the HTML display for `span#map-state` and `span#map-city`.

Now that we have a function to pull the address portions, we need to call it somewhere! I have two places where I have decided was the best time to update the location. When the maps is loaded (on page load) and anytime the map has been moved or zoomed.

So, we write an if statement to check if [`maps.isLoaded()`](http://code.google.com/apis/maps/documentation/javascript/v2/reference.html#GMap2.isLoaded) and then we need to [`addListenter()`](http://code.google.com/apis/maps/documentation/javascript/v2/reference.html#GEvent.addListener) to event `moveend`.

```
// once map has fully loaded, update current location in view
if(map.isLoaded()) { updateMapLocation(); }

// update location in view upon changing the map in any way
GEvent.addListener(map, 'moveend', function() { updateMapLocation(); });
```