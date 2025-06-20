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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                bree: ['Bree Serif', ...defaultTheme.fontFamily.sans],
                serif: ['Slabo 27px', ...defaultTheme.fontFamily.serif],
            },
        },
    },

<<<<<<< HEAD
    plugins: [forms],

=======
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/line-clamp'),
    ],
>>>>>>> a3d85c4d6ca99ddd8d2645c222d55ec1af33d323
};
