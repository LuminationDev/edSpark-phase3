// const autoprefixer = require('autoprefixer');

// /** @type {import('tailwindcss').Config} */
module.exports = {
    mode: 'jit',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.{ vue, js, ts, jsx, tsx }',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors:{
                adviceYellow: '#FFC836',
                adviceGreen: '#048246',
                adminTeal: '#339999'
            }
        },
    },
    plugins: [
        require('@tailwindcss/line-clamp')
    ],
}
