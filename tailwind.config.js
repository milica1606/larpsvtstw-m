/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class', /* can be used with lightswitcher from Skeleton UI */
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.{js,ts}",
        "./resources/**/*.svelte",
        require('path').join(require.resolve('@skeletonlabs/skeleton'), '../**/*.{html,js,svelte,ts}'), /* Skeleton UI */
    ],
    theme: {
        extend: {},
    },
    /* corePlugins: {
        aspectRatio: false,
    }, */
    plugins: [
        require("@tailwindcss/forms"),
        /* require("@tailwindcss/aspect-ratio") not needed for Tailwind version 3+, */
        ...require('@skeletonlabs/skeleton/tailwind/skeleton.cjs')(), /* Skeleton UI plugin to include files for color and design token classes */
    ],
}
