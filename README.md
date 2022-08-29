# Personal website and blog

Built with [Jigsaw](https://jigsaw.tighten.com/).

## Development

```
$ composer install
$ npm install
$ npm run watch
```

## Deployment

```
$ npm run prod
$ git commit -m "Build for deploy"
$ git subtree push --prefix build_production origin gh-pages
```