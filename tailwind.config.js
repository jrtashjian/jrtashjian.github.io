module.exports = {
  purge: [
    'source/**/*.blade.php',
    'source/**/*.md',
    'source/**/*.html',
  ],
  darkMode: 'media',
  theme: {
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
