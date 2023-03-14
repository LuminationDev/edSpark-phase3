<script>
    export default {

        mounted() {
            const footerSwoosh = document.querySelector('.footerSwoosh');
            const paths = footerSwoosh.querySelectorAll('path');

            paths.forEach(path => {
                console.log(path.width);
            })
        }
    }
</script>

<template>
    <!-- viewBox="0 0 1440 310" -->
    <!-- width="1440" -->
    <svg class="footerSwoosh"  height="310"  fill="none" xmlns="http://www.w3.org/2000/svg">
        <path class="w-full" d="M0 181.224C0 181.224 82.022 184.454 394.286 180.475C608.05 177.751 765.842 172.184 980.308 157.307C1376.97 129.791 1440 0 1440 0V276.5H0V181.224Z" fill="#339999" fill-opacity="0.25"/>
        <path class="w-full" d="M0 186.071C0 186.071 13.1868 190.232 325.451 186.071C539.215 183.223 727.599 177.538 942.593 165.057C1346.11 141.63 1440 23 1440 23V309.5H0V186.071Z" fill="#A0ADBC"/>
        <path class="w-full" d="M0 183.867C0 183.867 8.70331 183.867 319.648 183.867C533.437 183.867 735.757 178.191 950.769 166.872C1352.44 145.725 1440 41.5 1440 41.5V309.5H0V183.867Z" fill="#002855"/>
    </svg>
</template>
