import filament from '../../vendor/filament/support/tailwind.config.preset'

/** @type {import('tailwindcss').Config} */
export default {
    presets: [filament],
    content: [
        './app/Filament/**/*.php',
        './vendor/filament/**/*.blade.php',
        './resources/views/filament/**/*.blade.php',
    ],
};
