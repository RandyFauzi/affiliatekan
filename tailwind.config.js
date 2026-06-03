import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    content: [
        './app/**/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                brandOrange: '#FF6B00',
            },
            fontFamily: {
                sans: ['Manrope', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};
