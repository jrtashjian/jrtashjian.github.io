import hljs from 'highlight.js/lib/core';

hljs.registerLanguage( 'bash', require('highlight.js/lib/languages/bash') );
hljs.registerLanguage( 'css', require('highlight.js/lib/languages/css') );
hljs.registerLanguage( 'html', require('highlight.js/lib/languages/xml') );
hljs.registerLanguage( 'javascript', require('highlight.js/lib/languages/javascript') );
hljs.registerLanguage( 'json', require('highlight.js/lib/languages/json') );
hljs.registerLanguage( 'php', require('highlight.js/lib/languages/php') );
hljs.registerLanguage( 'scss', require('highlight.js/lib/languages/scss') );

document.querySelectorAll( 'code' ).forEach( ( block ) => hljs.highlightElement( block ) );