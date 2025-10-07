import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#0d9488',   // vert turquoise bois
                secondary: '#1e293b', // gris foncé
                accent: '#38bdf8',    // bleu clair
                darkBg: '#0f0f0f',    // fond général sombre
                darkCard: '#1f2937',  // cartes sombres
                darkText: '#e5e7eb',  // texte clair
                darkMuted: '#94a3b8', // gris doux
            },
            boxShadow: {
                soft: '0 8px 30px rgba(0, 0, 0, 0.3)',
            },
            keyframes: {
                gradient: {
                    '0%, 100%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                },
            },
            animation: {
                gradient: 'gradient 15s ease infinite',
            },
        },
    },

    plugins: [forms],
};
