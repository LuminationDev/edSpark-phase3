/** @type {import('tailwindcss').Config} */
import colors from 'tailwindcss/colors'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'

module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        './vendor/filament/**/*.blade.php',
    ],
    safelist: [
        'bg-[url(*)]',
        'bg-[#4A5568]',
        'bg-[#e53935]',
        'bg-[#DD6B20]',
        'bg-[#D69E2E]',
        'bg-[#38A169]',
        'bg-[#319795]',
        'bg-[#3182CE]',
        'bg-[#5A67D8]',
        'bg-[#6B46C1]',
        'bg-[#4338CA]',
        'bg-[#D53F8C]',
        'bg-[#ffa000]',
        'bg-[#C7B2EA]'
    ],
    theme: {
        extend: {
            colors: {
                main: {
                    navy: 'rgba(0, 40, 88, 1)',
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
                custom: {
                    genericLightBlue: 'rgba(180, 216, 241, 0.35)', // light blue - table header
                    genericLighterBlue: 'rgba(180, 216, 241, 0.19)', // Even lighter blue
                    genericScrollbarDark: '#0A0045', // only repeated coz i screwed something up with scrollbar customisation
                },
                event:{
                    virtual: '#BF123D',
                    hybrid: '#A855F7',
                    inPerson: '#3B82F6'

                },

                adviceYellow: '#FFC836',
                adviceGreen: '#048246',
                adminTeal: '#339999',
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
            }
        },
    },
    plugins: [
        forms,
        typography,
    ],
}

