/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    navy: '#002858',
                    darkTeal: '#0A7982',
                    teal: '#339999',
                    lightTeal: '#28D5CB',
                },
                secondary: {
                    blue: '#0072DA',
                    lightBlue: '#44B8F3',
                    darkBlue: '#1C5CA9',
                    green: '#048246',
                    lightGreen: '#A5D1BD',
                    purple: '#8866C5',
                    lightPurple: '#8866C5',
                    yellow: '#FFC836',
                    lightYellow: '#F5E1AD',
                    red: '#DE4668',
                    lightRed: '#F7C1C5',

                },

                adviceYellow: '#FFC836',
                adviceGreen: '#048246',
                adminTeal: '#339999'
            },
            spacing: {
                extraSmall: '12px',
                small: '18px',
                medium: '24px',
                large: '36px',
                extraLarge: '48px',
                huge: '81px',
                extraHuge: '140px'
            },
        },
    },
    plugins: [
        require('@tailwindcss/line-clamp')
    ],
}

