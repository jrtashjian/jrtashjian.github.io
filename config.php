<?php

return [
    'production' => false,
    'baseUrl' => '',

    'siteName' => 'JR Tashjian',
    'siteDescription' => 'Senior Software Engineer',
    'siteAuthor' => 'JR Tashjian',

    'collections' => [
        'posts' => [
            'path' => '/{date|Y/m}/{-title}',
            'sort' => ['-date','title'],
        ],
    ],
    // Helpers
    'getDate' => function ( $page ) {
        return Datetime::createFromFormat( 'U', $page->date );
    },
];
