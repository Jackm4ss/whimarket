/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
    "./public/**/*.html"
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
        handwriting: ['"Caveat"', 'cursive'],
      },
      colors: {
        brand: {
          purple: '#4F26A6',
          'purple-dark': '#3E1D85',
          'purple-deep': '#4F18C8',
          'purple-banner': '#501EB4',
          'purple-newsletter': '#5B27B5',
          'purple-light': '#F3EEFF',
          yellow: '#F59E0B',
          'yellow-light': '#FEF9EE',
          gold: '#FDBA2D',
          dark: '#111827',
          gray: '#6B7280',
          'gray-muted': '#4B5563',
        }
      },
      screens: {
        'xs': '475px',
      }
    },
  },
  plugins: [],
}
