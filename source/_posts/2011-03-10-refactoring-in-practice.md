---
extends: _layouts.post
title: Refactoring in Practice
date: 2011-03-10
categories: [code]
---
I love taking larger functions and slimming them down into a more efficient and readable format. Here is a simple example of just that. The original function:

```
function strip_slashes( $data )
{
    if( is_array($data) )
    {
        foreach($data as $key => $val )
        {
            $data[$key] = strip_slashes($val);
        }
    }
    else
    {
        return stripslashes($data);
    }

    return $data;
}
```

The refactored function:

```
function strip_slashes( $data )
{
    return is_array($data) ? array_map('strip_slashes', $data) : stripslashes($data);
}
```

The code is much more readable and can be understood easily. Try to do the same with your own functions. The more you practice refactoring old code the easier it will be to remember how to do things quicker.