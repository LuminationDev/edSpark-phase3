/** @type {import('tailwindcss').Config} */
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
import colors from 'tailwindcss/colors'

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
    '#be123c', // mbRose - Better accessibility
    '#002858', //navy

    // light theme colours for breadcrumbs
    '#9F7AEA',
    '#C6F6D5',
    '#BEE3F8',
    '#e57373',
    '#BEE3F8',
    '#DDD6FE',
    '#B2F5EA',
    '#FFF1CB',
    '#FFDECF',
    '#FECCD0',
    '#DBCCF5',
    '#6e99ce',

    // theme colours for selector on editor page
    '#DE4668',
    '#FF8D78',
    '#8866C5',

    // dark theme colours for icons
    '#185E69',
    '#FFC836',
    '#0072DA',
    '#AEDCF3',
    '#965A00',

    // new secondary color style guide
    '#FFC836',
    '#965A00',
    '#DE4668',
    '#FF8D78',
    '#8866C5',
    '#0072DA',
    '#319795',
    '#002858',

];

const generateSafeList = (colors) : Array<string> => {
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
                    yellow: '#FFC836',
                    lightYellow: '#F5E1AD',
                    red: '#C73E5D', //was #de4668
                    lightRed: '#F7C1C5',
                    mbRose: '#be123c',
                    mbIcons: 'green',
                    coolGrey: '#D9DAE4',
                    // new style guide
                    banana: '#FFC836',
                    bananadark: '#965A00',
                    cherry:  '#DE4668',
                    peach: '#FF8D78',
                    grape:  '#8866C5',
                    blueberry: '#0072DA',
                    teal: '#319795',
                    navy: '#002858',

                },
                custom: {
                    genericLightBlue: 'rgba(180, 216, 241, 0.35)', // light blue - table header
                    genericLighterBlue: 'rgba(180, 216, 241, 0.19)', // Even lighter blue
                    genericScrollbarDark: '#0A0045', // only repeated coz i screwed something up with scrollbar customisation
                },
                event: {
                    virtual: '#DE4668', // same as cherry
                    hybrid: '#8866C5', // same as grape
                    inPerson: '#0072DA', // same as blueberry
                    virtualTag: '#ffd5de'
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
                extraHuge: '140px',
                mainHero: '500px'
            },
            screens: {
                'ml': '860px',
            },
            scale: {
                '90': '0.9',
            },
            fontFamily:{
                museoSans:['Museo sans', 'sans-serif']
            }
        },
    },
    plugins: [
        forms,
        typography,
    ],
}

