import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', //=

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                darkBg: '#1a1a1a',     // Fond sombre
                darkCard: '#2a2a2a',   // Cartes sombres
                darkText: '#e5e5e5',   // Texte clair
                darkMuted: '#a1a1a1',  // Texte secondaire
            },
        },
    },

    plugins: [forms],
};
