module.exports = {
  purge: [
    'source/**/*.blade.php',
    'source/**/*.md',
    'source/**/*.html',
  ],
  darkMode: 'media',
  theme: {
    fontFamily: {
      'mono': [ 'Fira Code', 'ui-monospace', 'SFMono-Regular', 'Menlo', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', 'monospace' ],
    },
    extend: {
      colors: {
        'accent-dark': '#ffd128',
        'accent-light': '#285ddf',
      }
    },
  },
  // variants: {
  //   extend: {},
  // },
  // plugins: [],
};
