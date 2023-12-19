import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    extend: {
        colors: {
            danger: '#e11d48',
            primary: '#339999',
            success: '#15803d',
            warning: '#339999',

        },
    },
}
