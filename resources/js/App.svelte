<script lang="ts">
    import { onMount } from 'svelte';
    import {Route, router, active} from 'tinro';
    import type { TinroRoute } from 'tinro/index';

    import '@skeletonlabs/skeleton/themes/theme-modern.css';
    import { AppShell, AppBar, menu, AppRail, AppRailTile, Avatar, Toast, toastStore } from '@skeletonlabs/skeleton';
    import type { ToastSettings } from '@skeletonlabs/skeleton';

    import {user} from './auth';
   	import Authguard from './components/Authguard.svelte';
    // import Layout from './components/Layout.svelte';
    import UserProfile from './views/UserProfile.svelte';
    import LoginForm from './views/LoginForm.svelte';
    import { writable, type Writable } from 'svelte/store';

    const storeValue: Writable<number> = writable(1);

    //import axios from 'axios';
    //axios.defaults.withCredentials = true;

    export let url:string;
    let userRoles:string[];

    $: if ($user.roles) {
        userRoles = $user.roles.map((el) => {
            return el.name;
        });
    }

    onMount(() => {
        // if there is an url from browser then try to use it as a client route
        if (url)
            router.goto('/' + url);
    });

    router.subscribe((opts: TinroRoute ) => {
        if (userRoles) {
            const roleIndex:number = userRoles.indexOf(opts.path.split('/')[2]) + 1;
            $storeValue = roleIndex;
        }
    });

    function logout() {
        // $user=false;
        fetch('http://localhost:8000/api/logout', { method: 'POST', body: JSON.stringify({}), headers: {
            'Content-Type': 'application/json' }})
        .then((response) => {
            if (response.status === 200) {
                return response.json();
            } else if (response.status >= 400 || response.status < 500) {
                throw new Error(response.status + ': ' + response.statusText);
            } else {
                throw new Error('Something went wrong on API server!');
            }
        })
        .then((data) => {
            console.log(data.message);
            if (data.message) {
                $user = { id: 0, roles:[] };
            }
            const t: ToastSettings = {
                message: 'Logged out successfully',
                // Optional: Presets for primary | secondary | tertiary | warning
                preset: 'success',
                // Optional: The auto-hide settings
                autohide: true,
                timeout: 5000,
                // Optional: Adds a custom action button
                /* action: {
                    label: 'Login Success',
                    response: () => alert('Logged in successfully')
                } */
            };
            toastStore.trigger(t);
        })
        .catch((error) => {
            console.error(error);
        });
    }
</script>
<!-- PAGE WITH SKELETON UI -->
<Toast />
<AppShell slotSidebarLeft="w-[19%]" slotSidebarRight="w-[19%]">
    <svelte:fragment slot="header">
        <AppBar slotTrail="flex-col lg:flex-row" slotLead="flex-col lg:flex-row">
            <svelte:fragment slot="lead">
                <img alt="conf_logo" class="w-12" src="storage/public/scconf.jpg" />
                <p class="font-bold hidden sm:block mx-4">Scientific Conference</p>
            </svelte:fragment>
            <!--<button use:menu={{ menu: 'example' }}>Trigger</button>
            <nav class="list-nav card p-4 w-64 shadow-xl" data-menu="example"> -->
                <ul class="text-center">
                    <li class="navitem"><a href="/#/home" use:active>Home</a></li>
                    <li class="navitem"><a href="/#/shared" use:active>Shared page</a></li>
                    <li class="navitem"><a href="/#/blog" use:active>Blog</a></li>
                    <Authguard>
                        <svelte:fragment slot="authed">
                            <li class="navitem"><a href="/#/profile" use:active>User's profile</a></li>
                            {#each userRoles as role}
                                <li class="navitem roleitem"><a href="/#/role/{role}" use:active>{role}</a></li>
                            {/each}
                            <li class="navitem"><a class="option w-full" href="/" on:click={logout}>Logout</a></li>
                        </svelte:fragment>
                        <svelte:fragment slot="not_authed">
                            <li class="navitem"><a href="/#/login" use:active>Login</a></li>
                        </svelte:fragment>
                    </Authguard>

                </ul>
            <!-- </nav> -->
            <svelte:fragment slot="trail">
                <Authguard>
                    <svelte:fragment slot="authed">
                            <p><Avatar width={'w-16'} src={$user.user_img} /></p>
                            <p>{$user.first_name + ' ' + $user.last_name}</p>
                    </svelte:fragment>
                </Authguard>
            </svelte:fragment>
        </AppBar>
    </svelte:fragment>
    <svelte:fragment slot="sidebarLeft">
        <!-- <p>Role menus</p> JUST TAKES PLACE -->
        <AppRail selected={storeValue} class="text-token">
            <Authguard>
                <svelte:fragment slot="authed">
                    <AppRailTile class="h-20" label="Admin" title="Admin" tag="a" href="/role/Admin" value={1}>
                        <svg class="fill-current stroke-current" fill="currentColor" height="30px" width="30px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        viewBox="0 0 474.565 474.565" xml:space="preserve">
                            <g>
                                <path d="M255.204,102.3c-0.606-11.321-12.176-9.395-23.465-9.395C240.078,95.126,247.967,98.216,255.204,102.3z"/>
                                <path d="M134.524,73.928c-43.825,0-63.997,55.471-28.963,83.37c11.943-31.89,35.718-54.788,66.886-63.826
                                    C163.921,81.685,150.146,73.928,134.524,73.928z"/>
                                <path d="M43.987,148.617c1.786,5.731,4.1,11.229,6.849,16.438L36.44,179.459c-3.866,3.866-3.866,10.141,0,14.015l25.375,25.383
                                    c1.848,1.848,4.38,2.888,7.019,2.888c2.61,0,5.125-1.04,7.005-2.888l14.38-14.404c2.158,1.142,4.55,1.842,6.785,2.827
                                    c0-0.164-0.016-0.334-0.016-0.498c0-11.771,1.352-22.875,3.759-33.302c-17.362-11.174-28.947-30.57-28.947-52.715
                                    c0-34.592,28.139-62.739,62.723-62.739c23.418,0,43.637,13.037,54.43,32.084c11.523-1.429,22.347-1.429,35.376,1.033
                                    c-1.676-5.07-3.648-10.032-6.118-14.683l14.396-14.411c1.878-1.856,2.918-4.38,2.918-7.004c0-2.625-1.04-5.148-2.918-7.004
                                    l-25.361-25.367c-1.94-1.941-4.472-2.904-7.003-2.904c-2.532,0-5.063,0.963-6.989,2.904l-14.442,14.411
                                    c-5.217-2.764-10.699-5.078-16.444-6.825V9.9c0-5.466-4.411-9.9-9.893-9.9h-35.888c-5.451,0-9.909,4.434-9.909,9.9v20.359
                                    c-5.73,1.747-11.213,4.061-16.446,6.825L75.839,22.689c-1.942-1.941-4.473-2.904-7.005-2.904c-2.531,0-5.077,0.963-7.003,2.896
                                    L36.44,48.048c-1.848,1.864-2.888,4.379-2.888,7.012c0,2.632,1.04,5.148,2.888,7.004l14.396,14.403
                                    c-2.75,5.218-5.063,10.708-6.817,16.438H23.675c-5.482,0-9.909,4.441-9.909,9.915v35.889c0,5.458,4.427,9.908,9.909,9.908H43.987z"
                                    />
                                <path d="M354.871,340.654c15.872-8.705,26.773-25.367,26.773-44.703c0-28.217-22.967-51.168-51.184-51.168
                                    c-9.923,0-19.118,2.966-26.975,7.873c-4.705,18.728-12.113,36.642-21.803,52.202C309.152,310.022,334.357,322.531,354.871,340.654z
                                    "/>
                                <path d="M460.782,276.588c0-5.909-4.799-10.693-10.685-10.693H428.14c-1.896-6.189-4.411-12.121-7.393-17.75l15.544-15.544
                                    c2.02-2.004,3.137-4.721,3.137-7.555c0-2.835-1.118-5.553-3.137-7.563l-27.363-27.371c-2.08-2.09-4.829-3.138-7.561-3.138
                                    c-2.734,0-5.467,1.048-7.547,3.138l-15.576,15.552c-5.623-2.982-11.539-5.481-17.751-7.369v-21.958
                                    c0-5.901-4.768-10.685-10.669-10.685H311.11c-2.594,0-4.877,1.04-6.739,2.578c3.26,11.895,5.046,24.793,5.046,38.552
                                    c0,8.735-0.682,17.604-1.956,26.423c7.205-2.656,14.876-4.324,22.999-4.324c36.99,0,67.086,30.089,67.086,67.07
                                    c0,23.637-12.345,44.353-30.872,56.303c13.48,14.784,24.195,32.324,31.168,51.976c1.148,0.396,2.344,0.684,3.54,0.684
                                    c2.733,0,5.467-1.04,7.563-3.13l27.379-27.371c2.004-2.004,3.106-4.721,3.106-7.555s-1.102-5.551-3.106-7.563l-15.576-15.552
                                    c2.982-5.621,5.497-11.555,7.393-17.75h21.957c2.826,0,5.575-1.118,7.563-3.138c2.004-1.996,3.138-4.72,3.138-7.555
                                    L460.782,276.588z"/>
                                <path d="M376.038,413.906c-16.602-48.848-60.471-82.445-111.113-87.018c-16.958,17.958-37.954,29.351-61.731,29.351
                                    c-23.759,0-44.771-11.392-61.713-29.351c-50.672,4.573-94.543,38.17-111.145,87.026l-9.177,27.013
                                    c-2.625,7.773-1.368,16.338,3.416,23.007c4.783,6.671,12.486,10.631,20.685,10.631h315.853c8.215,0,15.918-3.96,20.702-10.631
                                    c4.767-6.669,6.041-15.234,3.4-23.007L376.038,413.906z"/>
                                <path d="M120.842,206.782c0,60.589,36.883,125.603,82.352,125.603c45.487,0,82.368-65.014,82.368-125.603
                                    C285.563,81.188,120.842,80.939,120.842,206.782z"/>
                            </g>
                        </svg>
                    </AppRailTile>
                    <AppRailTile class="h-20" label="Editor" title="Editor" tag="a" href="/role/Editor" value={2}>
                        <svg class="fill-current stroke-inherit" fill="currentColor" version="1.1" id="Capa_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="30px" height="30px" viewBox="0 0 53.58 53.581" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M5.994,48.428c-0.405-6.235,1.854-11.505,1.962-11.75c0.231-0.527,0.845-0.766,1.371-0.536
                                        c0.527,0.23,0.767,0.845,0.538,1.37c-0.026,0.061-2.6,6.079-1.607,12.496c1.582,1.413,5.323,3.216,14.082,3.107
                                        c8.302,0.104,12.092-1.511,13.816-2.884c0-0.007-0.002-0.013-0.001-0.017c1.086-6.518-1.553-12.642-1.58-12.701
                                        c-0.23-0.527,0.011-1.142,0.537-1.372c0.53-0.233,1.143,0.01,1.371,0.536c0.107,0.245,2.363,5.509,1.963,11.739
                                        c5.826,0.187,6.231-2.484,6.231-2.484c0.072-7.902-1.797-12.141-3.09-14.439s-7.039-3.951-10.774-5.173
                                        c-2.88-0.94-2.905-2.787-2.766-3.584c2.359-2.29,4.208-5.814,4.208-9.777c0-6.903-3.304-12.5-9.77-12.5
                                        c-7.327,0-9.77,5.597-9.77,12.5c0,3.747,1.655,7.099,3.83,9.39h-0.009c0,0,1.066,2.751-2.669,3.971
                                        c-3.735,1.222-9.482,2.874-10.776,5.173c-1.293,2.299-3.161,6.537-3.089,14.439C0.001,45.93,0.387,48.537,5.994,48.428z"/>
                                    <path d="M39.82,18.986h10.635c1.725,0,3.125-1.4,3.125-3.125V7.397c0-1.727-1.4-3.125-3.125-3.125H37.434
                                        c-1.724,0-3.125,1.398-3.125,3.125v6.267l-2.695,10.347L39.82,18.986z M43.745,15.597l-0.975-0.423l2.908-6.679l0.975,0.423
                                        L43.745,15.597z M44.29,5.981c0.235-0.538,1.11-0.675,1.955-0.307c0.845,0.369,1.341,1.104,1.106,1.639
                                        c-0.236,0.54-0.425,0.976-0.425,0.976l-3.062-1.333L44.29,5.981z M43.593,7.584l1.529,0.667l-2.908,6.68l-1.531-0.668
                                        L43.593,7.584z M43.55,16.045l-2.656,1.79l-0.404-3.125L43.55,16.045z"/>
                                </g>
                            </g>
                        </svg>
                    </AppRailTile>
                    <AppRailTile class="h-20" label="Reviewer" title="Reviewer" tag="a" href="/role/Reviewer" value={3}>
                        <svg class="fill-current stroke-inherit" fill="currentColor" version="1.1" id="Capa_3" xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" viewBox="0 0 24 24">
                            <path d="M 10 2 C 7.790861 2 6 3.790861 6 6 C 6 8.209139 7.790861 10 10 10 C 12.209139 10 14 8.209139 14 6 C 14 3.790861 12.209139 2 10 2 z M 10 12 C 3.9 12 2 16 2 16 L 2 18 L 10.59375 18 C 10.28975 16.023 10.88625 13.92625 12.40625 12.40625 C 12.45525 12.35725 12.5125 12.32825 12.5625 12.28125 C 11.8015 12.11425 10.96 12 10 12 z M 17 12 C 15.72 12 14.44475 12.49175 13.46875 13.46875 C 11.51575 15.42175 11.51575 18.57825 13.46875 20.53125 C 15.42175 22.48425 18.57825 22.48425 20.53125 20.53125 C 22.48325 18.57925 22.48325 15.42175 20.53125 13.46875 C 19.55425 12.49175 18.279 12 17 12 z M 18.78125 14.78125 L 20.21875 16.21875 L 16.5 19.90625 L 13.78125 17.21875 L 15.21875 15.78125 L 16.5 17.09375 L 18.78125 14.78125 z"/>
                        </svg>
                    </AppRailTile>
                    <AppRailTile class="h-20" label="Author" title="Author" tag="a" href="/role/Author" value={4}>
                        <svg class="fill-current stroke-inherit" fill="currentColor" version="1.1" id="Capa_4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="30px" viewBox="0 0 121.05 122.88"
                         style="enable-background:new 0 0 121.05 122.88" xml:space="preserve">
                            <style type="text/css">.st0{fill-rule:evenodd;clip-rule:evenodd;}</style>
                            <g>
                                <path class="st0" d="M30.31,58.08l5.63-0.14l2.66-0.07l4.05,4.7c0.95,1.1,1.92,2.21,2.98,3.28c-0.75,1.12-4.12,3.98-7.11,6.51 c-1.32,1.12-2.58,2.19-3.55,3.06c-7.59,2.95-15.01,5.97-20.76,9.59c-8.04,5.04-12.1,13.44-14.02,22.51 c-0.59,7.98-0.01,7.58,5.6,7.49c24.86-0.24,49.78,0.2,74.66,0.23c-0.34-3.39-0.03-6.98,1.01-10.65 c2.63-9.22,9.36-17.05,17.85-21.93c-5.06-2.66-10.94-5.03-16.93-7.34c-0.69-0.62-1.47-1.29-2.26-1.99c-2.52-2.2-5.35-4.69-6.5-6.11 c1.8-1.58,3.32-3.39,4.82-5.2l2.54-3.06c0.04-0.05,0.23-0.27,0.52-0.6c0.14-0.17,0.3-0.34,0.46-0.54h5.55 C95.7,40.29,90.5,14.28,73.77,3.26C68.62-0.13,64.93,0,58.91,0c-6.9,0-10.42,0.22-16.33,4.13C33.86,9.89,28.51,19.86,26.26,33.7 C25.79,40.6,25.49,52.52,30.31,58.08L30.31,58.08L30.31,58.08z M112.92,87.71l7.28,5.61c0.96,0.74,1.14,2.13,0.4,3.09l-1.89,2.36 l-10.77-8.31l1.89-2.36C110.57,87.15,111.96,86.97,112.92,87.71L112.92,87.71L112.92,87.71z M105.08,117.28 c-2.48,1.13-4.95,2.26-7.43,3.39c-5.82,2.66-5.64,3.91-4.73-2.15l1.42-9.53l0,0l-0.01-0.01l11.93-16.4l10.77,8.31l-11.94,16.4 L105.08,117.28L105.08,117.28L105.08,117.28z M96.17,110.41l7.07,5.46c-1.63,0.74-3.26,1.48-4.88,2.22 c-3.83,1.74-3.7,2.56-3.11-1.42L96.17,110.41L96.17,110.41L96.17,110.41z M39.92,55.57c-4.58-15.9-2.35-30.52,10.18-43.04 c2.22,7.19,7.21,13.13,15.7,17.51c4.06,3.01,7.99,6.65,11.78,10.83c0.67-2.77-1.9-6.15-5.01-9.61c2.89,1.43,5.53,3.41,7.41,7.25 c2.18,4.45,2.15,8.2,1.43,13.03c-0.21,1.4-0.5,2.73-0.87,4.01c-0.25,0.1-0.47,0.24-0.62,0.43c-0.34,0.42-0.69,0.83-1.01,1.2 c-0.1,0.12-0.18,0.21-0.53,0.63l-2.54,3.06c-1.94,2.33-3.9,4.66-6.38,6.36c-2.37,1.62-5.28,2.69-9.27,2.68 c-3.67-0.01-6.46-1.04-8.75-2.58c-2.41-1.6-4.34-3.8-6.2-5.94l-4.64-5.39C40.43,55.8,40.19,55.66,39.92,55.57L39.92,55.57 L39.92,55.57z M77.75,74.62c-2.4-2.1-4.91-4.34-6.14-5.85l-0.09,0.06c-2.9,1.97-6.46,3.3-11.35,3.29 c-4.56-0.01-7.99-1.28-10.8-3.15c-0.67-0.45-1.3-0.93-1.9-1.43c-1.4,1.75-4.51,4.39-7.31,6.76l-0.61,0.52 C39.11,90.54,73.16,95.3,77.75,74.62L77.75,74.62L77.75,74.62z"/>
                            </g>
                        </svg>
                    </AppRailTile>
                    <AppRailTile class="h-20" label="Organizer" title="Organizer" tag="a" href="/role/Organizer" value={5}>
                        <svg class="fill-current stroke-inherit" fill="currentColor" version="1.1" id="Capa_5" xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" viewBox="0 0 2600.000000 2600.000000" preserveAspectRatio="xMidYMid meet">
                            <metadata>
                            Created by potrace 1.15, written by Peter Selinger 2001-2017
                            </metadata>
                            <g transform="translate(0.000000,2600.000000) scale(0.100000,-0.100000)"
                            fill="currentColor" stroke="none">
                                <path d="M13127 21229 c-352 -25 -719 -163 -1007 -378 -96 -71 -265 -235 -343
                                -331 -414 -513 -536 -1195 -326 -1824 131 -390 405 -749 747 -978 l53 -36 -88
                                -57 c-134 -88 -248 -182 -369 -307 -344 -355 -538 -791 -574 -1289 -6 -89 -10
                                -682 -10 -1536 l0 -1393 765 0 765 0 -5 23 c-13 58 -25 215 -25 343 0 460 118
                                883 355 1270 368 601 975 1014 1665 1134 152 27 370 41 498 33 l102 -6 0 59
                                c0 269 -94 623 -235 891 -170 322 -446 620 -737 794 -32 19 -58 36 -58 39 0 3
                                41 34 91 70 156 110 330 282 444 440 522 725 473 1719 -117 2390 -289 328
                                -665 541 -1103 625 -71 14 -326 37 -365 33 -8 0 -64 -4 -123 -9z"/>
                                <path d="M5260 18744 c-948 -149 -1641 -954 -1640 -1906 0 -414 133 -811 383
                                -1148 120 -162 330 -357 507 -471 11 -8 12 -12 3 -15 -31 -13 -215 -143 -284
                                -200 -147 -122 -315 -312 -420 -474 -145 -225 -251 -501 -299 -785 -26 -157
                                -30 -438 -30 -2592 l0 -2243 1673 0 1672 0 388 1283 387 1282 0 1042 c0 878
                                -2 1061 -15 1160 -53 408 -213 767 -481 1085 -123 144 -325 318 -477 409 -32
                                18 -57 36 -57 39 0 3 29 24 64 47 332 224 601 581 732 972 250 745 19 1576
                                -579 2078 -254 213 -555 357 -882 424 -144 29 -498 36 -645 13z"/>
                                <path d="M21770 18743 c-632 -96 -1163 -485 -1446 -1058 -74 -151 -123 -295
                                -161 -474 -25 -117 -27 -143 -27 -371 0 -228 2 -254 26 -370 69 -320 199 -598
                                396 -842 85 -105 264 -273 372 -349 50 -35 89 -67 88 -70 -2 -4 -50 -38 -108
                                -77 -483 -323 -790 -805 -897 -1407 -14 -78 -16 -237 -20 -1165 l-3 -1075 389
                                -1285 389 -1285 1671 -3 1671 -2 -3 2372 -3 2373 -22 110 c-90 458 -279 818
                                -589 1122 -111 109 -256 223 -365 287 -27 15 -48 31 -48 35 0 3 41 36 92 71
                                177 124 389 350 511 543 64 100 144 269 186 394 238 698 61 1463 -459 1983
                                -292 291 -647 472 -1065 540 -146 24 -429 26 -575 3z"/>
                                <path d="M14875 15374 c-640 -97 -1176 -487 -1455 -1057 -110 -225 -170 -446
                                -192 -704 -39 -465 124 -981 431 -1357 113 -140 293 -305 417 -383 24 -15 44
                                -30 44 -33 0 -3 -25 -21 -57 -39 -158 -95 -376 -288 -509 -451 -243 -298 -388
                                -627 -445 -1005 -18 -126 -19 -208 -19 -2467 l0 -2338 2055 0 2055 0 -3 2388
                                c-3 2374 -3 2388 -24 2488 -72 348 -195 624 -395 887 -134 176 -337 362 -520
                                477 -48 30 -88 57 -87 60 0 3 34 28 77 57 514 347 847 1019 819 1653 -13 283
                                -70 504 -197 765 -280 575 -819 964 -1467 1060 -127 19 -404 18 -528 -1z"/>
                                <path d="M8411 12628 c-17 -54 -1491 -4931 -1497 -4955 l-6 -23 2830 0 2831 0
                                4 1308 c4 1406 2 1357 56 1617 65 307 199 623 381 899 78 117 126 179 274 351
                                l28 34 -65 82 c-138 172 -264 384 -343 574 -24 60 -49 117 -53 125 -7 13 -266
                                15 -2219 18 l-2210 2 -11 -32z"/>
                                <path d="M17443 12648 c-4 -7 -26 -59 -49 -114 -82 -202 -209 -416 -349 -592
                                l-67 -83 49 -55 c193 -222 303 -380 414 -600 123 -244 205 -501 250 -789 23
                                -139 23 -153 26 -1452 l4 -1313 1484 0 1484 0 -13 43 c-35 115 -394 1302 -919
                                3037 l-582 1925 -862 3 c-683 2 -863 0 -870 -10z"/>
                            </g>
                        </svg>
                    </AppRailTile>
                    <AppRailTile class="h-20" label="Typewriter" title="Typewriter" tag="a" href="/role/Typewriter" value={6}>
                        <svg class="fill-current stroke-inherit" fill="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" viewBox="0 0 2600.000000 2600.000000" preserveAspectRatio="xMidYMid meet">
                            <metadata>
                            Created by potrace 1.15, written by Peter Selinger 2001-2017
                            </metadata>
                            <g transform="translate(0.000000,2600.000000) scale(0.100000,-0.100000)"
                            fill="currentColor" stroke="none">
                            <path d="M9310 19549 c-167 -33 -304 -181 -330 -356 -6 -42 -10 -622 -10
                            -1530 l0 -1463 -292 0 c-228 0 -308 -4 -358 -16 -141 -33 -249 -130 -303 -272
                            -22 -57 -22 -60 -25 -984 l-3 -928 -234 0 -234 0 -3 573 -3 572 -32 67 c-41
                            88 -113 160 -201 201 l-67 32 -1100 0 -1100 0 -66 -31 c-84 -40 -158 -114
                            -201 -201 -31 -64 -33 -74 -33 -173 1 -145 23 -200 119 -296 53 -53 86 -77
                            131 -95 l60 -24 833 -3 832 -2 0 -559 c0 -522 1 -561 19 -613 48 -139 164
                            -246 303 -278 25 -5 239 -10 511 -10 l467 0 0 -536 0 -536 -1409 -2158 c-775
                            -1186 -1419 -2176 -1431 -2200 -50 -98 -53 -118 -57 -440 -3 -179 -1 -335 5
                            -378 21 -151 109 -273 240 -335 65 -31 71 -32 225 -35 86 -2 157 -7 157 -10 0
                            -4 -40 -90 -89 -192 -157 -325 -289 -654 -339 -840 -21 -81 -25 -118 -26 -250
                            -1 -136 2 -164 22 -230 37 -117 89 -207 169 -291 l72 -75 -160 -291 c-180
                            -327 -193 -362 -166 -453 32 -108 127 -182 237 -184 73 -1 155 37 196 90 25
                            33 386 679 452 809 30 61 37 124 18 187 -22 74 -61 117 -156 168 -157 84 -205
                            173 -181 330 37 240 345 922 725 1603 63 114 393 658 732 1208 661 1072 643
                            1046 765 1110 29 16 55 27 57 25 2 -2 -144 -271 -325 -597 -318 -578 -327
                            -596 -331 -657 -6 -81 17 -142 74 -198 57 -58 126 -78 243 -72 94 5 146 25
                            198 76 16 16 231 400 478 853 274 502 464 840 486 864 38 41 145 112 155 102
                            3 -3 -161 -352 -365 -775 -204 -424 -375 -791 -381 -817 -25 -113 41 -233 155
                            -281 22 -9 70 -14 138 -14 92 0 109 3 156 27 34 16 64 41 83 68 16 23 240 487
                            498 1031 402 850 475 996 516 1042 42 48 119 100 130 89 3 -2 -174 -438 -392
                            -968 -391 -949 -397 -965 -398 -1034 0 -83 21 -132 83 -188 49 -44 121 -67
                            211 -67 128 0 218 47 264 139 13 25 135 325 272 666 236 588 251 622 294 665
                            25 25 66 56 92 68 48 22 88 29 88 14 0 -5 -151 -453 -336 -996 -358 -1053
                            -355 -1045 -320 -1130 25 -60 65 -104 119 -131 47 -23 61 -25 185 -25 247 0
                            242 -5 488 445 103 187 201 355 218 373 48 51 141 93 212 94 32 1 66 -2 75 -8
                            14 -8 -50 -138 -440 -906 -480 -943 -534 -1039 -721 -1278 -46 -58 -140 -162
                            -209 -231 -293 -293 -589 -455 -1009 -549 -73 -16 -145 -36 -160 -44 -32 -17
                            -84 -71 -102 -105 -33 -63 -282 -752 -287 -792 -13 -108 53 -225 149 -264 97
                            -38 212 -13 279 62 38 42 58 88 189 448 41 110 76 203 80 207 3 4 63 23 133
                            43 434 119 787 331 1117 672 275 284 446 534 691 1013 l177 345 2123 0 2124 0
                            90 -305 c50 -168 112 -355 139 -415 308 -709 709 -1102 1314 -1292 97 -30 124
                            -42 127 -58 3 -11 23 -137 45 -280 22 -143 45 -273 51 -290 16 -50 60 -105
                            103 -130 162 -95 342 -10 376 177 9 48 3 101 -50 439 -82 519 -77 510 -351
                            579 -330 82 -528 188 -729 389 -166 166 -301 377 -414 645 -75 176 -580 1879
                            -566 1906 26 48 158 66 235 31 23 -10 52 -35 67 -57 16 -23 73 -177 138 -369
                            61 -181 121 -345 133 -363 31 -47 99 -89 165 -103 82 -17 247 -7 303 19 92 41
                            148 126 149 222 0 31 -56 496 -125 1033 -69 538 -125 991 -125 1007 0 39 42
                            48 112 23 96 -34 89 -13 227 -738 89 -469 127 -652 144 -685 26 -51 91 -104
                            145 -120 50 -14 196 -13 244 2 90 27 169 134 170 230 0 28 -89 502 -198 1054
                            -109 552 -195 1006 -192 1010 4 3 30 -2 58 -12 39 -14 60 -30 86 -65 31 -43
                            52 -118 293 -1046 142 -549 266 -1015 274 -1034 18 -44 67 -93 121 -122 59
                            -31 230 -32 297 -1 90 42 149 132 149 228 0 30 -385 1511 -436 1680 -6 17 -2
                            18 36 13 49 -6 109 -43 143 -89 17 -22 98 -255 247 -708 121 -371 246 -751
                            277 -845 54 -164 57 -172 107 -220 31 -31 67 -55 92 -63 60 -18 201 -15 259 5
                            92 31 165 136 165 237 0 21 -370 1160 -428 1314 -6 17 37 16 79 -2 52 -22 114
                            -71 137 -108 42 -68 829 -2072 910 -2318 186 -563 322 -1107 353 -1410 28
                            -269 -48 -413 -274 -523 -61 -30 -106 -60 -130 -86 -44 -49 -72 -135 -63 -195
                            4 -22 70 -229 148 -458 117 -349 146 -424 174 -456 113 -132 331 -105 408 50
                            46 92 40 130 -74 473 l-100 300 26 20 c148 110 236 210 299 340 74 152 90 230
                            90 440 -1 151 -5 205 -28 335 -34 191 -85 419 -137 611 -22 81 -40 149 -40
                            153 0 3 195 7 433 8 l432 3 58 27 c115 54 211 174 238 296 6 32 9 177 7 398
                            l-3 349 -26 65 c-17 42 -536 844 -1445 2235 l-1419 2170 -5 1895 -5 1895 -22
                            53 c-47 114 -131 196 -252 244 -53 21 -72 23 -378 26 l-323 3 -2 1517 -3 1517
                            -22 55 c-46 112 -143 205 -258 247 -48 17 -203 18 -4095 19 -2302 1 -4064 -2
                            -4090 -7z m7630 -2089 l0 -1260 -3565 0 -3565 0 0 1260 0 1260 3565 0 3565 0
                            0 -1260z m-7431 -6127 c0 -5 -67 -168 -148 -363 l-147 -355 -392 -3 c-216 -1
                            -392 0 -392 3 0 3 81 167 180 365 l180 360 360 0 c198 0 360 -3 359 -7z m1181
                            0 c0 -5 -44 -169 -97 -365 l-98 -358 -392 0 c-216 0 -393 2 -393 5 0 3 214
                            601 255 713 3 9 86 12 365 12 198 0 360 -3 360 -7z m1180 -15 c0 -13 -20 -171
                            -45 -353 -25 -181 -45 -336 -45 -342 0 -10 -86 -13 -395 -13 l-395 0 5 23 c3
                            12 36 166 75 342 39 176 72 330 75 343 l5 22 360 0 360 0 0 -22z m1190 -343
                            l0 -365 -395 0 -394 0 5 48 c7 64 54 650 54 668 0 12 55 14 365 14 l365 0 0
                            -365z m1180 357 c0 -5 23 -165 50 -357 28 -192 50 -352 50 -357 0 -4 -177 -8
                            -393 -8 l-393 0 -17 328 c-10 180 -20 344 -23 365 l-6 37 366 0 c201 0 366 -4
                            366 -8z m1184 -9 c32 -107 196 -696 196 -704 0 -6 -145 -8 -392 -7 l-392 3
                            -68 340 c-36 187 -67 350 -67 363 l-1 22 359 0 c334 0 360 -1 365 -17z m1330
                            -351 l153 -362 -395 2 -395 3 -119 355 c-65 195 -118 358 -118 363 0 4 162 6
                            361 5 l360 -3 153 -363z m1223 18 c106 -190 198 -353 204 -362 9 -17 -12 -18
                            -384 -18 l-394 0 -168 353 c-92 193 -170 358 -172 365 -4 10 69 12 357 10
                            l363 -3 194 -345z m-8987 -852 c-19 -5 -66 -19 -105 -30 -176 -50 -372 -197
                            -475 -357 l-16 -23 -71 25 c-95 35 -157 42 -238 28 -37 -7 -69 -10 -72 -8 -2
                            3 38 88 89 191 l93 186 415 -1 c294 -1 405 -4 380 -11z m2720 -6 c0 -10 -20
                            -169 -45 -353 -25 -184 -45 -337 -45 -341 0 -5 -146 -8 -323 -8 l-324 0 -19
                            62 c-24 79 -84 173 -129 200 -24 14 -34 26 -31 37 3 9 24 102 46 206 23 105
                            43 196 45 203 3 9 95 12 415 12 391 0 410 -1 410 -18z m1350 -342 l0 -360
                            -440 0 c-345 0 -440 3 -440 13 -1 6 12 168 27 360 l27 347 413 0 413 0 0 -360z
                            m1350 351 c0 -5 23 -162 50 -350 28 -188 50 -346 50 -351 0 -6 -159 -10 -445
                            -10 l-445 0 0 33 c0 40 -27 515 -35 615 l-6 72 416 0 c235 0 415 -4 415 -9z
                            m1419 -253 l75 -261 -24 -41 c-13 -23 -31 -66 -41 -96 l-17 -55 -376 -3 -376
                            -2 -5 22 c-10 48 -135 684 -135 691 0 4 186 7 413 7 l412 0 74 -262z m639 185
                            c1 -43 8 -109 16 -148 8 -38 13 -71 11 -73 -2 -2 -32 2 -66 9 l-62 14 -44 130
                            c-23 71 -43 133 -43 137 0 4 42 8 93 8 l94 0 1 -77z m2056 -60 c42 -75 75
                            -137 74 -139 -4 -3 -126 -44 -132 -44 -3 0 -15 19 -27 43 -48 94 -164 218
                            -244 260 -29 16 -24 16 112 17 l141 0 76 -137z m-7013 -1380 c-24 -186 -46
                            -340 -48 -342 -2 -2 -23 10 -48 28 -94 68 -235 111 -360 111 -105 0 -207 -24
                            -358 -85 -13 -6 7 63 90 309 l108 316 331 0 330 0 -45 -337z m1559 -23 l0
                            -360 -496 0 -496 0 6 58 c4 31 15 170 26 307 11 138 22 274 26 303 l5 52 465
                            0 464 0 0 -360z m1510 352 c0 -5 23 -165 50 -357 28 -192 50 -350 50 -352 0
                            -2 -223 -3 -495 -3 l-495 0 0 23 c0 12 -9 168 -20 347 -11 179 -20 331 -20
                            338 0 9 100 12 465 12 256 0 465 -4 465 -8z m1275 -14 c6 -31 75 -579 75 -602
                            0 -18 -1 -18 -18 -3 -26 24 -138 73 -213 93 -96 27 -284 25 -364 -3 -33 -12
                            -61 -20 -63 -19 -1 2 -25 118 -52 257 -28 140 -52 264 -55 277 l-5 22 345 0
                            345 0 5 -22z"/>
                            </g>
                        </svg>
                    </AppRailTile>
                </svelte:fragment>
            </Authguard>
        </AppRail>
    </svelte:fragment>
    <svelte:fragment slot="sidebarRight">
        <p>Right Sidebar</p>
    </svelte:fragment>
    <svelte:fragment slot="footer">
        <p class="text-center">from { url ? url : 'root' } @ Laravel + Vite + Svelte + Tailwind CSS &copy; 2023</p>
    </svelte:fragment>
    <!-- DEFAULT SLOT -->
    <Route path="/" redirect="/home"/>
    <Route path="/home"><h1>It is main page</h1></Route>
    <Route path="/shared"><h1>It is shared page</h1></Route>
    <Authguard>
        <div slot="authed">
            <Route path="/login/*" redirect="/role/Author"/>
            <Route path="/profile">
                <UserProfile />
            </Route>
            {#each userRoles as role}
                <Route path="/role/{role}">Content for {role} role</Route>
            {/each}
        </div>
        <div slot="not_authed">
            <Route path="/profile/*" redirect="/login"/>
            <Route path="/role/*" redirect="/login"/>
            <Route path="/login">
                <LoginForm />
            </Route>
        </div>
    </Authguard>
    <Route fallback><p>No page found</p></Route>
</AppShell>
<style>
    .navitem {
        @apply inline-block px-4 py-2 mx-2 rounded-token font-token text-token hover:bg-primary-hover-token; /*token means from theme */
    }
    .roleitem {
        @apply hidden xl:inline-block;
    }
</style>
<!-- -->
<!-- LAYOUT USAGE
<Layout wholeScreen>
    <span slot="header">
        <h1 class="text-center">{ name } Laravel + Vite + Svelte + Tailwind CSS</h1>
        <nav class="text-center">
            <a href="/#/" use:active exact>Home</a>
            <a href="/#/shared" use:active>Shared page</a>
                <Authguard>
                    <span slot="authed">
                        <a href="/#/profile" use:active>User's profile</a>
                        <a href="/#/login" use:active on:click={logout}>Logout</a>
                    </span>
                    <span slot="not_authed">
                        <a href="/#/login" use:active>Login</a>
                    </span>
              </Authguard>
        </nav>
    </span>
    <span slot="main">
        <Route path="/"><h1>It is main page</h1></Route>
        <Route path="/shared"><h1>It is shared page</h1></Route>
        <Authguard>
            <div slot="authed">
                <Route path="/login/*" redirect="/profile"/>
                <Route path="/profile">
                    <UserProfile />
                </Route>
            </div>
            <div slot="not_authed">
                <Route path="/profile/*" redirect="/login"/>
                <Route path="/login">
                    <LoginForm />
                </Route>
            </div>
        </Authguard>
    </span>
    <span slot="nav">
        <p>Navigation</p>
    </span>
    <div slot="footer" class="text-center">
        { name } Laravel + Vite + Svelte + Tailwind CSS &copy; 2023
    </div>
</Layout>
<style>
    :global(.active){font-weight: bold; text-decoration: underline}
    a {outline : none}
</style>
-->
<!-- SIDEBAR WITH HOVERABLE SVG ICONS EXAMPLE
<aside class="ml-[-100%] z-10 top-0 pb-3 px-6 w-full flex flex-col justify-between h-screen border-r bg-white transition duration-300 md:w-4/12 lg:ml-0 lg:w-[25%] xl:w-[20%] 2xl:w-[15%]">
    <div>
        <div class="-mx-6 px-6 py-4">
            <a href="#" title="home">
                <img src="https://tailus.io/sources/blocks/stats-cards/preview/images/logo.svg" class="w-32" alt="tailus logo">
            </a>
        </div>

        <div class="mt-8 text-center">
            <img src="https://tailus.io/sources/blocks/stats-cards/preview/images/second_user.webp" alt="" class="w-10 h-10 m-auto rounded-full object-cover lg:w-28 lg:h-28">
            <h5 class="hidden mt-4 text-xl font-semibold text-gray-600 lg:block">Cynthia J. Watts</h5>
            <span class="hidden text-gray-400 lg:block">Admin</span>
        </div>

        <ul class="space-y-2 tracking-wide mt-8">
            <li>
                <a href="#" aria-label="dashboard" class="relative px-4 py-3 flex items-center space-x-4 rounded-xl text-white bg-gradient-to-r from-sky-600 to-cyan-400">
                    <svg class="-ml-1 h-6 w-6" viewBox="0 0 24 24" fill="none">
                        <path d="M6 8a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V8ZM6 15a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2v-1Z" class="fill-current text-cyan-400 dark:fill-slate-600"></path>
                        <path d="M13 8a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2V8Z" class="fill-current text-cyan-200 group-hover:text-cyan-300"></path>
                        <path d="M13 15a2 2 0 0 1 2-2h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-1Z" class="fill-current group-hover:text-sky-300"></path>
                    </svg>
                    <span class="-mr-1 font-medium">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#" class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path class="fill-current text-gray-300 group-hover:text-cyan-300" fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z" clip-rule="evenodd" />
                        <path class="fill-current text-gray-600 group-hover:text-cyan-600" d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z" />
                    </svg>
                    <span class="group-hover:text-gray-700">Categories</span>
                </a>
            </li>
            <li>
                <a href="#" class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path class="fill-current text-gray-600 group-hover:text-cyan-600" fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd" />
                        <path class="fill-current text-gray-300 group-hover:text-cyan-300" d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z" />
                    </svg>
                    <span class="group-hover:text-gray-700">Reports</span>
                </a>
            </li>
            <li>
                <a href="#" class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path class="fill-current text-gray-600 group-hover:text-cyan-600" d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                        <path class="fill-current text-gray-300 group-hover:text-cyan-300" d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                    </svg>
                    <span class="group-hover:text-gray-700">Other data</span>
                </a>
            </li>
            <li>
                <a href="#" class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path class="fill-current text-gray-300 group-hover:text-cyan-300" d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" />
                        <path class="fill-current text-gray-600 group-hover:text-cyan-600" fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" />
                    </svg>
                    <span class="group-hover:text-gray-700">Finance</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="px-6 -mx-6 pt-4 flex justify-between items-center border-t">
        <button class="px-4 py-3 flex items-center space-x-4 rounded-md text-gray-600 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="group-hover:text-gray-700">Logout</span>
        </button>
    </div>
</aside>
-->
<!-- TINRO ROUTER SUBROUTES EXAMPLE
    <nav>
        <a href="/">Home</a>
        <a href="/portfolio">Portfolio</a>
        <a href="/contacts">Contacts</a>
    </nav>

    <Route path="/"><h1>This is the main page</h1></Route>
    <Route path="/portfolio/*">
        <Route path="/">
            <h1>Portfolio introduction</h1>
            <nav>
                <a href="/portfolio/sites">Sites</a>
                <a href="/portfolio/photos">Photos</a>
            </nav>
        </Route>
        <Route path="/sites"><h1>Portfolio: Sites</h1></Route>
        <Route path="/photos"><h1>Portfolio: Photos</h1></Route>
    </Route>
    <Route path="/contacts"><Contacts /></Route>
-->

