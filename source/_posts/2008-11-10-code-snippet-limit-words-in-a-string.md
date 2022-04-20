---
extends: _layouts.post
title: Limit Words in a String
date: 2008-11-10
categories: [code]
---
While developing websites, I've frequently run into clients who would like a news system integrated on their site. A news system consists of a list of articles, usually a summary at first, and when an article has been selected, the full article is displayed. So, how do you extract the summary from the content without duplicating content?

If you wanted to do this effect quickly, you could just use the function [`substr()`](http://php.net/substr). However, the [`substr()`](http://php.net/substr) function only limits the number of characters being displayed. The returned result would be an excerpt of text that may or may not have the ending word cut-off.

The purpose behind this function is to limit the number of words displayed in such a way that the ending word is not cut-off. Personally, I think this small change makes the site look a little nicer.

```
function limit_words( $string, $word_limit ) {
    $words = explode( " ", $string );
    return implode( " ", array_splice( $words, 0, $word_limit ) );
}
```

To use this function, pass the text you would like to extract the excerpt from, as `$string`. Then, set the number of words you would like to display as `$word_limit`. The function will return the excerpt as a string.

The function separates the string where it finds a space, therefore separating each word using the [`explode()`](http://php.net/explode) function. Each word is put into an array called `$words`. We then cut out the excerpt using the number of words we would like to display (`$word_limit`) starting from the beginning using the [`array_splice()`](http://php.net/array_splice) function. With the excerpt extracted from the full text, we then recreate the string by adding spaces after each key (word) in the array using the [`implode()`](http://php.net/implode) function.

```
# Example Usage
$content = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
echo limit_words( $content, 20 );
```

The above example would output this result:

```
Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut
```

Simple function, Nice results. Enjoy!