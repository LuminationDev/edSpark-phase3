// const autoprefixer = require('autoprefixer');

// /** @type {import('tailwindcss').Config} */
module.exports = {
    mode: 'jit',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.{ vue, js, ts, jsx, tsx }'
    ],
    theme: {
        extend: {
            colors:{
                adviceYellow: '#FFC836',
                adviceGreen: '#048246'
            }
        },
    },
    plugins: [
        require('@tailwindcss/line-clamp')
    ],
}
