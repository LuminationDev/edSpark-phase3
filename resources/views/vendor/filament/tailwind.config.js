const colors = require('tailwindcss/colors')
const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    content: [
        './packages/admin/resources/**/*.blade.php',
        './packages/forms/resources/**/*.blade.php',
        './packages/notifications/resources/**/*.blade.php',
        './packages/support/resources/**/*.blade.php',
        './packages/tables/resources/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                danger: colors.rose,
                primary: '#339999',
                success: colors.green,
                warning: '#339999',
            },
            fontFamily: {
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
