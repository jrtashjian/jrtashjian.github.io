---
extends: _layouts.post
title: Validate terminal command in PHP
date: 2010-11-09
categories: [code]
---
Testing if a function exists in PHP is easy. Testing if a command exists on the system is also easy. I've never really run into the problem of having to create a compatibility suite script to make sure my PHP script will run without trouble. Simple things like checking the version of PHP installed or the version of MySQL are straightforward. This time though, I needed to make sure a command line program existed on the system which I invoke using [`exec()`](http://php.net/function.exec). First and foremost, we need to check that `exec()` exists and we can use it. If it does, we then need to use it to execute a command on the server which will output the path to our command. I will be testing for `tar` in the example.

```
if ( function_exists( 'exec' ) ) {
    // send test command to system
    exec( 'command -v tar >& /dev/null && echo "Found" || echo "Not Found"', $output );

    if ( $output[0] == "Found" ) {
        // command is available
        return TRUE;
    } else {
        // command is unavailable
        return FALSE;
    }
}
```

Our focus will be this line:

```
exec( 'command -v tar >& /dev/null && echo "Found" || echo "Not Found"', $output );
```

In the first part of the command we will run `command` with the `-v` option. The `-v` option causes the output of the `_command_` to be displayed or return zero if the `_command_` is not found. Here's a short description of the command:

```
SYNTAX
    command [-pVv] _command_ [arguments ...]
OPTIONS
    -P  Use a default path
    -v  Verbose
    -V  More verbose
```

The next part of the command we use `>&` which is a metacharacter in Unix which tells the command to redirect the standard output and standard error. Which in this case, we redirect the output to a file `/dev/null`. We do this because we want to handle the response of the command with the last part. The last part of the command we use `&&` which is another metacharacter which tells Unix to execute the following command only if the preceding command succeeds. We also use the `||` metacharacter which tells Unix to execute the following command if the preceding command fails. To understand it better, it's just like writing an `if-then-else` statement:

```
If( command -v tar >& /dev/null ) Then
    echo "Found"
Else
    echo "Not Found"
End If
```

Now we need to bring the response back to PHP. We do that with the second parameter of `exec();` `$output`. Every line of output from the command will be returned in `$output` as an array which we can then run our conditional against. Short, simple, easy little command. Just replace `tar` with the command you'd like to check for. You could even take the code and place it into a function to make it easily reusable.