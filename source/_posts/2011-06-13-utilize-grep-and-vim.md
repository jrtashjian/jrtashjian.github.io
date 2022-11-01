---
extends: _layouts.post
title: Utilize Grep and Vim
date: 2011-06-13
---
I was working on a client's huge website for the past couple weeks that required searching many files for instances of a string. My workflow began running [grep](https://wikipedia.org/wiki/Grep) in the entire directory.

```
grep -rin "phrase to search" *
```

The command outputs the path to file and the line number the phrase was found on.

```
path-to-file.php:78: phrase to search
path-to-file.php:51: phrase to search
path-to-file.php:10: phrase to search
```

I would then open the file in [Vim](https://wikipedia.org/wiki/Vim_(text_editor)) at the line number returned, make my edits and save. I would repeat it for each result from the grep.

```
vim path-to-file.php +78
vim path-to-file.php +51
vim path-to-file.php +10
```

After a few times going through this process, I felt there had to be a faster, more efficient way. Of course there is! Instead of doing each command separately I can pass the output of grep into a Vim buffer and open each file from inside Vim.

```
grep -rin "phrase to search" * | vim -
```

What we are doing in the above command is running the original grep command and passing the output as the input of the proceeding command using the [pipe `|` symbol](https://wikipedia.org/wiki/Vertical_bar#Pipe). When running Vim, we pass the dash `-` symbol instead of a file name which tells Vim to create a new buffer with the output of the previous command (reads from [`STDIN`](https://wikipedia.org/wiki/Stdin)).

So now, we see the same output as previously displayed but this time we can interact with it inside Vim.

```
path-to-file.php:78: phrase to search
path-to-file.php:51: phrase to search
path-to-file.php:10: phrase to search
```

All we have to do now is navigate to the line in the return of the file we'd like to open using Vim's built in goto-file command `gf`. When on a line, hitting `gf` will open that file, but that's not exactly the result we want. We want it to go to the specific line number! Well, all we have to do is hit `gF` (shift-f) and it'll do just that. Make your edits and save the file normally. You can then quit the buffer with `:bd` and you'll be returned to the output again where you can repeat the process.

Enjoy!