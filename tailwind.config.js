/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#1E40AF',    // bleu custom
        secondary: '#FACC15',  // jaune custom
      },
      fontFamily: {
        sans: ['"Instrument Sans"', 'ui-sans-serif', 'system-ui'],
      },
      spacing: {
        128: '32rem',  // exemple spacing custom
      },
    },
  },
  plugins: [],
}
