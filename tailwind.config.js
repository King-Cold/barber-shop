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
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                barber: ['Oswald', 'sans-serif'],
                elegant: ['Playfair Display', 'serif'],
                cinzel: ['Cinzel', 'serif'],
            },
            colors: {
                'dark-carbon': '#1C1C1C',
                'vintage-gold': '#D4AF37',
                'crimson-red': '#8B0000',
                'chalk-white': '#F8F8F8',
                'barber-black': '#0F0F0F',
                'barber-gold': '#C5A059',
                'barber-silver': '#BDBDBD',
                'slate-dark': '#1F232D',
                'bronze-gold': '#8B7355',
            },
        },
    },

    plugins: [forms],
};
