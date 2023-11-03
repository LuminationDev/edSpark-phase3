/** @type {import('tailwindcss').Config} */
import colors from 'tailwindcss/colors'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

const edsparkColor = [
    '#4A5568',
    '#e53935',
    '#DD6B20',
    '#D69E2E',
    '#38A169',
    '#319795',
    '#3182CE',
    '#5A67D8',
    '#6B46C1',
    '#4338CA',
    '#D53F8C',
    '#ffa000',
    '#C7B2EA',
    '#be123c' // mbRose - Better accessibility
];

const generateSafeList = (colors) => {
    const safeList = [];
    ['text', 'bg', 'hover:text', 'fill', 'stroke'].forEach((prefix) => {
        colors.forEach((color) => {
            safeList.push(`${prefix}-[${color}]`);
        });
    });
    return safeList;
}




module.exports = {
    darkMode: 'class',
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './vendor/filament/**/*.blade.php',
    ],
    safelist: [
        ...generateSafeList(edsparkColor)
    ],
    theme: {
        extend: {
            colors: {
                main: {
                    navy: '#002858', //was rgba(0, 40, 88, 1)',
                    darkTeal: '#0A7982',
                    teal: '#097982', //was '#339999',
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
                    red: '#C73E5D', //was #de4668
                    lightRed: '#F7C1C5',
                    mbRose: '#be123c',
                    mbIcons: 'green',

                },
                custom: {
                    genericLightBlue: 'rgba(180, 216, 241, 0.35)', // light blue - table header
                    genericLighterBlue: 'rgba(180, 216, 241, 0.19)', // Even lighter blue
                    genericScrollbarDark: '#0A0045', // only repeated coz i screwed something up with scrollbar customisation
                },
                event:{
                    virtual: '#BE123C',
                    hybrid: '#A855F7',
                    inPerson: '#3B82F6'

                },

                adviceYellow: '#FFC836',
                adviceGreen: '#048246',
                adminTeal: '#2a8282', //was '#339999',
                danger: colors.rose,
                primary: colors.teal,
                success: colors.green,
                warning: colors.yellow,
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
            screens:{
                'ml': '860px',
            },
            scale:{
                '90': '0.9',
            }
        },
    },
    plugins: [
        forms,
        typography,
    ],
}

