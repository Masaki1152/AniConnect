import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './public/js/*.js',
        './src/**/*.{html,js}',
        './app/View/Components/**/*.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                textColor: '#333333',
                subTextColor: '#666666',
                baseColor: '#F8F8F8',
                mainColor: '#ABE1FA',
                accentColor: '#FF7F50',
                textColorHover: '#1a1a1a',
                mainColorHover: '#4EBDEB',
                accentColorHover: '#E75428',
                activeBorderBlue: '#1DA1F2',
                activeTextDark: '#000000',
                linkColor: '#0000EE',
            },
            screens: {
                // ヘッダー用 header_custom
                hc: '1080px',
            },
            listStyleType: {
                none: 'none',
                disc: 'disc',
                decimal: 'decimal',
                circle: 'circle',
                square: 'square',
            }
        },
    },

    plugins: [
        forms,
    ],
};
