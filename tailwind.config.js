import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                ntt: {
                    maroon: '#7A1F1F',
                    gold: '#D9A441',
                    charcoal: '#1F1A17',
                    cream: '#FAF3E3',
                    leaf: '#4F6F52',
                },
            },
        },
    },

    plugins: [forms],
};
