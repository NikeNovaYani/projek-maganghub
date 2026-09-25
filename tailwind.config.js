/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './app/Views/**/*.php',
    './public/**/*.js',
    './app/Config/Auth.php'
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        brand: {
          // Dominant cyan with lime-green to teal gradient accents
          mint: {
            50: '#effdfc',
            100: '#d6f8f5',
            200: '#b5f1ed',
            300: '#84e6e0',
            400: '#4ad9d4',
            500: '#2bc7c0',
            600: '#1eaaa5',
            700: '#168b89',
            800: '#126c6c',
            900: '#0f5557',
            950: '#09383d',
          },
          emerald: {
            50: '#f5fce8',
            100: '#e9fac8',
            200: '#d6f5a2',
            300: '#c7f68a',
            400: '#b8f27e',
            500: '#9cdb5b',
            600: '#7fbd3c',
            700: '#639c2f',
            800: '#4b7728',
            900: '#385b24',
            950: '#203817',
          },
          teal: {
            50: '#e8fffb',
            100: '#c8faf2',
            200: '#96f2e5',
            300: '#5ce3d1',
            400: '#27c9b8',
            500: '#0ead9d',
            600: '#0a9189',
            700: '#08766f',
            800: '#075b58',
            900: '#064a49',
            950: '#032f32',
          },
          cyan: {
            50: '#ecfeff',
            100: '#cffafe',
            200: '#a5f3fc',
            300: '#67e8f9',
            400: '#22d3ee',
            500: '#06b6d4',
            600: '#0891b2',
            700: '#0e7490',
            800: '#155e75',
            900: '#164e63',
            950: '#083344',
          },
          lime: {
            300: '#bef264',
            400: '#a3e635',
            500: '#84cc16',
            600: '#65a30d',
          }
        }
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
        heading: ['Outfit', 'Inter', 'sans-serif'],
      },
      animation: {
        'fade-in': 'fadeIn 0.5s ease-out',
        'fade-in-up': 'fadeInUp 0.6s ease-out',
        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'float': 'float 6s ease-in-out infinite',
        'marquee': 'marquee 22s linear infinite',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' },
        },
        fadeInUp: {
          '0%': { opacity: '0', transform: 'translateY(20px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-10px)' },
        },
        marquee: {
          '0%': { transform: 'translateX(0%)' },
          '100%': { transform: 'translateX(-50%)' },
        }
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
  ],
};
