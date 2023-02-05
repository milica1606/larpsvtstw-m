<script lang="ts">
    import SideMenus from './components/SideMenus.svelte';

    import { onMount } from 'svelte';
    import {Route, router, active} from 'tinro';
    import type { TinroRoute } from 'tinro/index';

    import '@skeletonlabs/skeleton/themes/theme-modern.css';
    import '@skeletonlabs/skeleton/styles/all.css';

    import { AppShell, AppBar, menu, Avatar, Toast, toastStore, Drawer, drawerStore } from '@skeletonlabs/skeleton';
    import type { ToastSettings, DrawerSettings } from '@skeletonlabs/skeleton';

    import Fa from 'svelte-fa/src/fa.svelte';
    import { faBars } from '@fortawesome/free-solid-svg-icons';

    import { user } from './auth';
   	import Authguard from './components/Authguard.svelte';
    // import Layout from './components/Layout.svelte';
    import UserProfile from './views/UserProfile.svelte';
    import LoginForm from './views/LoginForm.svelte';

    import { storeValue } from './storeValue';

    import { t, locale, locales } from './i18n/index';


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
        if (url) {
            router.goto('/' + url);
        }

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
            const ts: ToastSettings = {
                message: $t('logout_success'),
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
            toastStore.trigger(ts);
        })
        .catch((error) => {
            console.error(error);
        });
    }

    function openDrawer() {
        const settings: DrawerSettings = { id: 'drawer' };
	    drawerStore.open(settings);
    }

    function onChangeLocale() {
        /*  SERVER IS RESTARTED ON THIS REQUEST DUE TO .env FILE CHANGE - SHOULD BE ASSOCIATED TO A USER LOCALE PREFERENCE
        fetch('http://localhost:8000/api/lang', { method: 'PUT', body: JSON.stringify({locale: $locale}), headers: {
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
            if (data.message && data.message == 'OK') {
                const ts: ToastSettings = {
                    message: $t('locale_changed'),
                    // Optional: Presets for primary | secondary | tertiary | warning
                    preset: 'success',
                    // Optional: The auto-hide settings
                    autohide: true,
                    timeout: 5000,
                    // Optional: Adds a custom action button
                };
                toastStore.trigger(ts);
            }
        })
        .catch((error) => {
            if (error) {
                const ts: ToastSettings = {
                    message: error,
                    // Optional: Presets for primary | secondary | tertiary | warning
                    preset: 'error',
                    // Optional: The auto-hide settings
                    autohide: true,
                    timeout: 5000,
                    // Optional: Adds a custom action button
                };
                toastStore.trigger(ts);
            }
        });*/
        const ts: ToastSettings = {
                    message: $t('locale_changed'),
                    // Optional: Presets for primary | secondary | tertiary | warning
                    preset: 'success',
                    // Optional: The auto-hide settings
                    autohide: true,
                    timeout: 5000,
                    // Optional: Adds a custom action button
                };
                toastStore.trigger(ts);
    }
</script>
<!-- PAGE WITH SKELETON UI -->
<Toast /> <!-- GLOBALLY SCOPED COMPONENT-->
<Drawer>
    <div id="drawer">
        <SideMenus /> <!-- ANOTHER INSTANCE OF THE SideMenus COMPONENT TO BE COPIED INTO A DRAWER-->
    </div>
</Drawer> <!-- GLOBALLY SCOPED COMPONENT-->
<AppShell slotSidebarLeft="hidden lg:grid lg:w-[19%]" slotSidebarRight="w-[19%]">
    <svelte:fragment slot="header">
        <AppBar slotTrail="flex-col lg:flex-row" slotLead="flex-col lg:flex-row">
            <svelte:fragment slot="lead">
                <div>
                    <button class="btn-icon text-2xl lg:hidden inline align-middle" on:click={openDrawer}>
                        <Fa icon={faBars} />
                    </button>
                    <!-- <img alt="conf_logo" class="w-12" src="storage/public/scconf.jpg" /> -->
                    <svg class="inline align-middle" version="1.1" id="Layer_1" height="50px" width="50px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                        viewBox="0 0 2000 2000" enable-background="new 0 0 2000 2000" xml:space="preserve">
                        <g>
                            <g>
                                <g id="dHaBY6.tif">
                                    <g>
                                        <path fill="#2D9A64" d="M708.7,630.1c15.2,1.3,38.8,3.2,62.3,5.3c5.9,0.5,11.7,2.1,17.6,2.5c1.8,0.1,4.7-1.4,5.4-2.9
                                            c11-23.7,20.8-48.1,32.9-71.2c16.8-32.1,37.6-61.6,64.5-86.3c8.2-7.6,17.7-13.8,26.1-21.1c2.7-2.4,4.9-6.3,5.6-9.9
                                            c5.6-27.9,22-46.8,47.7-57.7c14.3-6,29.3-7.5,44.9-4c27.2,6.1,46,21.8,56.9,47.1c2.5,5.9,3.6,12.4,5.7,18.5
                                            c0.7,2.1,2,4.6,3.8,5.8c27.8,18.6,49.3,43.3,68.2,70.4c19.3,27.7,34.3,57.6,47.7,88.4c2.8,6.4,5,13.1,7.8,19.6
                                            c0.7,1.5,2.9,3.5,4.3,3.3c11.4-1.2,22.8-2.9,34.3-4.2c9.9-1.1,19.9-2.6,29.8-2.7c19-0.1,38.1,0.4,57.1,1.1
                                            c9.4,0.4,18.8,1.4,28.1,2.8c45.8,7.2,87.7,22.6,119,58.8c14.1,16.3,23.2,35.2,28.4,56.4c4.1,17.1,5.3,34.3,3.7,51.5
                                            c-1.8,19-6.2,37.6-12.5,55.7c-17.4,50-45.5,93.6-78.7,134.3c-3.8,4.7-3.5,8,0.2,12.5c25.9,31.3,48,65,65.2,101.8
                                            c8.5,18.1,15.9,36.6,19.7,56.4c1.7,8.8,2.8,17.7,4.9,26.5c0.7,3,2.6,6.4,5.1,8c18.6,12.4,31.3,28.6,36.5,50.9
                                            c8.7,37.4-11.4,74.5-45.2,89.2c-20.7,9-42.1,8.3-63.2-0.1c-2.1-0.9-5.3-1.1-7.3-0.1c-17.3,8.9-35.3,16.1-54.5,19.6
                                            c-15.4,2.8-30.9,5.5-46.5,6.8c-16.4,1.5-33.1,2.4-49.5,1.6c-20.5-1-40.9-3.9-61.3-6c-4.4-0.5-8.8-1.4-13.1-1.4
                                            c-1.4,0-3.6,2.1-4.2,3.6c-12.7,33-27.6,64.9-46.6,94.8c-21.4,33.6-46.2,64-80.3,85.9c-18,11.5-37,20.3-58.4,22.7
                                            c-9.7,1.1-19.5,2.4-29.2,1.8c-23.6-1.5-45.4-9.2-65.5-21.4c-33.9-20.6-59-49.8-80.7-82.4c-18.2-27.4-32.6-56.9-45.3-87.2
                                            c-2-4.7-3.5-9.7-5.6-14.3c-0.7-1.6-2.9-3.6-4.2-3.5c-11.8,1.3-23.5,3.1-35.3,4.8c-1.1,0.2-2.3,0.5-3.4,0.5
                                            c-24.4,0.8-48.9,3.1-73.2,1.9c-39.5-1.9-78.1-8.8-113.4-28.3c-1.6-0.9-4.5-0.6-6.4,0.2c-42.4,18.2-91.1-2.3-106.3-46
                                            c-12.6-36.3,3-75.2,34.7-94c2.3-1.4,4.2-5.1,4.5-7.9c4-38.1,18.3-72.5,36.2-105.7c15.1-27.9,33-54,53.2-78.5
                                            c4.1-4.9,4-8.2-0.1-13.2c-24.3-29.8-45.6-61.6-62.4-96.3c-9.5-19.5-17.2-39.7-22.3-61c-4.6-19.4-7.7-38.9-6.2-58.7
                                            c3.5-45.9,24-82.2,62.3-108c20.6-13.9,43.3-22.4,67.4-28C645.9,632.9,673,630.8,708.7,630.1z M779.9,998.1
                                            c1,26.8,1.8,53.8,3.3,80.7c0.7,13,2.5,26,4.1,38.9c0.2,1.9,1.9,4.1,3.5,5.3c18.1,12.5,36,25.2,54.5,37.1
                                            c47,30.5,96.2,56.9,147.1,80.3c4.1,1.9,7.8,3.7,12.9,1.1c25.1-12.9,50.8-24.8,75.8-38c45-23.7,87.8-51,129-80.8
                                            c1.2-0.9,2.4-2.5,2.7-4c0.9-3.9,1.6-7.9,1.8-11.9c1.6-24.4,4.2-48.8,4.4-73.1c0.2-38.7-1.2-77.4-2.1-116.1
                                            c-0.2-9.4-0.7-18.9-2.4-28.1c-1.1-5.8,0.8-12.6-5.5-17.2c-34.5-25.1-70.1-48.5-107.7-68.8c-32-17.2-64.6-33.4-97.1-49.7
                                            c-2.3-1.2-6.2-0.8-8.6,0.4c-25.7,12.5-51.6,24.8-76.9,38.2C873.7,816,831,843.2,790.1,873c-1.4,1-2.6,2.9-2.8,4.6
                                            c-1.6,13.4-3.4,26.7-4.1,40.1C781.7,944.4,781,971.2,779.9,998.1z M839.7,1348c0.6,2.3,0.9,4.6,1.7,6.7
                                            c5,11.7,9.9,23.4,15.3,34.9c14.8,31,32.1,60.4,55.7,85.6c20.1,21.3,42.8,38.4,72.9,43.7c18.3,3.2,35.3,0.3,52-6.8
                                            c27.6-11.7,48-32.2,65.8-55.5c21.5-28.1,37.4-59.4,51.3-91.7c2.4-5.5,4.1-11.2,6-16.5c-23-6.7-45.1-12.5-66.7-19.8
                                            c-27.5-9.2-54.6-19.9-82-29.5c-4.6-1.6-8.5-6-14.7-3.6c-28.9,11.1-57.7,22.4-87,32.3C887.3,1335.4,863.7,1341.1,839.7,1348z
                                            M776.9,684.2c-1.2-0.5-1.9-0.9-2.6-1c-18.5-2.1-36.9-4.9-55.5-6.1c-23.4-1.5-46.8,0-70.1,3.6c-19.6,3-38.3,8.2-56.2,16.6
                                            C560.7,712.3,541,736.9,536,772c-3.1,21.6,0.7,43,7.1,63.6c7.5,24,18,46.7,31.7,67.8c12.1,18.6,25.2,36.5,37.9,54.7
                                            c2.4,3.5,4.7,3.8,7.6,0.4c3.4-4,7.3-7.7,11-11.5c33.2-33.8,68.9-64.8,106.7-93.2c2.1-1.6,4.7-3.9,5.1-6.3
                                            c2-11.5,3.2-23.1,5-34.7c1.5-10.2,3.1-20.4,5.1-30.6c2.7-13.7,5.6-27.3,8.7-40.9c2.5-10.8,5.4-21.4,8.2-32.1
                                            C772.4,700.8,774.7,692.6,776.9,684.2z M1222.9,683.4c4,15.2,7.9,29.5,11.4,43.9c3,11.8,5.8,23.7,8.3,35.7
                                            c2.1,9.7,3.7,19.5,5.3,29.3c2.2,13.4,4.6,26.8,6.3,40.2c1.3,10.4,2.2,20.5,13.5,25.5c0.7,0.3,1.2,0.9,1.8,1.4
                                            c38.8,30.5,76.1,62.8,109.8,98.9c3.1,3.3,5.2,3.4,8.1-0.3c25.8-33.7,49.3-68.7,64-108.9c9.7-26.6,16.5-53.6,11.2-82.4
                                            c-2.3-12.6-6.5-24.1-13.6-34.7c-15.2-22.9-38-35.1-63-43.4c-24.9-8.2-50.8-11.5-76.9-11.6c-19.4-0.1-38.8,1.2-58.2,2.4
                                            C1241.7,679.9,1232.5,681.9,1222.9,683.4z M1222.8,1312.4c61.5,8.9,121.6,12,181-11.9c-11-24.6-11.6-48.5,0.7-71.9
                                            c12.3-23.4,32.2-36.6,58-41.8c-0.1-2.4,0.2-4.6-0.5-6.5c-4.9-15.4-9.1-31-15.2-45.9c-14.3-35.3-36.1-66-58.9-96.2
                                            c-3.1-4.2-5.4-4-8.9-0.3c-9.5,10-19.3,19.8-29.2,29.3c-28.2,27.2-58.3,52.2-89.3,76.2c-1.5,1.2-3.3,2.9-3.6,4.7
                                            c-1.2,6.2-1.7,12.5-2.6,18.8c-1.7,11.4-3.2,22.8-5.3,34.1c-2.6,14.3-5.5,28.5-8.6,42.7c-2.5,11.4-5.3,22.6-8.2,33.9
                                            C1229.2,1288.7,1226.1,1299.8,1222.8,1312.4z M596.6,1299.7c2.3,1.3,3.9,2.4,5.6,3.1c26.2,10.7,53.9,14.9,81.8,15.8
                                            c21.5,0.7,43.1-1,64.6-2.2c9.3-0.5,18.5-2.6,28.7-4.2c-2.1-7.3-4.2-13.6-5.8-20c-5-21-10.2-41.9-14.4-63
                                            c-4.5-22.7-7.5-45.7-11.9-68.4c-1.3-6.6-0.1-14-7.4-19.4c-42-30.9-80.7-65.6-116.7-103.2c-4.5-4.7-5.2-4.8-9,0.2
                                            c-30.5,39.6-57.5,81.1-71.2,129.9c-1.7,6-2.5,12.2-3.7,18.5c26.4,5.4,46.2,18.5,58.4,41.7
                                            C607.7,1251.8,607.3,1275.7,596.6,1299.7z M932.3,502.6c-33.4,25.4-93,120.3-91.8,145.6c8.8,2.4,17.6,4.7,26.4,7.3
                                            c19.2,5.6,38.6,10.6,57.4,17.1c23.6,8.2,46.9,17.7,70.1,27c4.6,1.8,8.3,0.9,12.3-0.8c8.8-3.7,17.5-7.4,26.4-10.7
                                            c16.2-6.1,32.4-12.1,48.7-17.7c13.1-4.5,26.5-8.4,39.8-12.2c12.6-3.6,25.3-6.7,38.2-10.1c-0.2-1.6-0.2-3-0.4-4.3
                                            c-0.1-0.8-0.3-1.5-0.6-2.2c-12.3-29.5-25.7-58.3-43.5-85c-12.3-18.4-25.9-35.6-42.6-50.3c-3.7-3.2-5.5-3.8-9.2,1
                                            c-20.4,26.1-46.7,37.5-80,30.8c-17.8-3.6-32-12.7-43.7-26.2C937.4,509.1,935,505.9,932.3,502.6z M1202.2,811
                                            c0.5-0.3,1-0.6,1.5-0.9c-0.3-3-0.5-6-1-9c-1.1-6.2-2.5-12.3-3.8-18.5c-2.5-12.3-4.7-24.8-7.7-37c-4-15.9-9-31.5-12.8-47.3
                                            c-1.4-5.7-4.1-6.3-8.5-5.1c-13.2,3.6-26.5,6.9-39.6,10.9c-20.2,6.1-40.2,12.7-60.2,19.2c-3.5,1.1-6.8,3.1-11.3,5.2
                                            C1109.3,753.3,1156.6,780.7,1202.2,811z M1060,1266.5c0,0.6-0.1,1.3-0.1,1.9c10.7,3.8,21.3,7.8,32.1,11.3
                                            c13.4,4.4,26.8,8.5,40.3,12.4c13,3.8,26.1,7.1,39.2,10.5c2.7,0.7,5.4,0.6,6.3-3.1c4.6-17.1,9.6-34.1,13.7-51.4
                                            c4.3-18.2,7.7-36.6,11.4-55c0.5-2.6,0.1-5.4,0.1-9.3C1156.5,1215,1109.1,1242.4,1060,1266.5z M940.1,1268.1
                                            c-49.5-25.8-96.8-53.3-143.9-84.5c0.6,4.9,0.7,8.1,1.3,11.2c1.1,6.2,2.4,12.4,3.7,18.5c2.5,12.3,4.8,24.8,7.7,37
                                            c3.3,13.7,7.5,27.2,10.8,40.9c2.9,12,2.6,12.1,14.6,9.6c0.2,0,0.4,0,0.6-0.1c13.1-3.3,26.3-6.3,39.3-10.1
                                            c16-4.8,31.8-10.2,47.7-15.6C928,1273,934,1270.5,940.1,1268.1z M796.1,810c0.6,0.3,1.1,0.7,1.7,1
                                            c45.5-30.6,93.2-57.6,143.1-82.5c-2.8-1.5-4.3-2.6-6-3.2c-14.4-4.9-28.7-10-43.2-14.4c-20.8-6.3-41.7-12-62.5-17.8
                                            c-3.8-1.1-6.4-0.2-7.4,4.6c-1.4,6.9-4,13.5-5.8,20.3c-3.5,13.8-6.9,27.7-10.2,41.6c-2.2,9.5-4.3,19-6.1,28.5
                                            C798.3,795.3,797.3,802.7,796.1,810z M1262.8,1080.2c0.6,0.2,1.2,0.3,1.9,0.5c0.5-0.4,1.1-0.9,1.6-1.3
                                            c28.9-23.7,56.4-49,81.9-76.3c3.7-4,4.2-6.3,0-10.2c-23.3-21.9-46.3-44.2-69.5-66.1c-4.4-4.2-9.5-7.6-14.3-11.4
                                            c-0.5,0.2-1.1,0.5-1.6,0.7C1266.7,970.7,1266.7,1025.5,1262.8,1080.2z M737.2,914.1c-2.4,1.7-3.2,2.2-3.8,2.7
                                            c-29.2,24-56.8,49.7-82.7,77.1c-3.5,3.7-1.9,5.9,0.8,8.5c23.3,22.2,46.6,44.5,70,66.6c4.3,4.1,9.3,7.6,14,11.3
                                            c0.6-0.1,1.1-0.2,1.7-0.4C733.3,1025.2,733.3,970.4,737.2,914.1z"/>
                                        <path fill="#2D9A64" d="M1101.7,998c0,55.8-45.7,101.8-102,101.6c-56.2-0.2-100.8-45.7-101.4-100.4
                                            c-0.6-57.3,45.6-103.8,103.4-103C1056,896.9,1101.7,941.2,1101.7,998z"/>
                                    </g>
                                </g>
                            </g>
                            <g>
                                <path fill="#990000" d="M1000,76.5c-316.2,0-595.4,158.7-762.6,400.6l179.1,103.4C547.5,399.9,760,282.4,1000,282.4
                                    c239.9,0,452.5,117.5,583.5,298.1l179.1-103.4C1595.4,235.1,1316.1,76.5,1000,76.5z"/>
                                <path fill="#006699" d="M139.9,658.6c-42.7,106.4-66.3,222.6-66.3,344.3c0,476.6,360.5,869.4,823.5,920.7v-207.6
                                    c-349-50-617.6-350.3-617.6-713.1c0-84.2,14.4-165,41-240.1L139.9,658.6z"/>
                                <path fill="#006699" d="M1860.1,658.6l-180.7,104.2c26.6,75.1,41,155.9,41,240.1c0,362.7-268.6,663.1-617.5,713.1v207.6
                                    c462.9-51.2,823.4-444.1,823.4-920.7C1926.4,881.2,1902.8,765.1,1860.1,658.6z"/>
                            </g>
                        </g>
                    </svg>
                </div>
                <p class="font-bold hidden sm:block mx-4">{$t('Scientific Conference')}</p>
            </svelte:fragment>
            <!--<button use:menu={{ menu: 'example' }}>Trigger</button>
            <nav class="list-nav card p-4 w-64 shadow-xl" data-menu="example"> -->
                <ul class="text-center">
                    <li class="navitem"><a href="/#/home" use:active>{$t('Home')}</a></li>
                    <li class="navitem"><a href="/#/shared" use:active>{$t('Shared page')}</a></li>
                    <li class="navitem"><a href="/#/blog" use:active>Blog</a></li>
                    <Authguard>
                        <svelte:fragment slot="authed">
                            <li class="navitem"><a href="/#/profile" use:active>{$t("User's profile")}</a></li>
                            {#each userRoles as role}
                                <li class="navitem roleitem"><a href="/#/role/{role}" use:active>{$t(role)}</a></li>
                            {/each}
                            <li class="navitem"><a class="option w-full" href="/" on:click={logout}>{$t('Logout')}</a></li>
                        </svelte:fragment>
                        <svelte:fragment slot="not_authed">
                            <li class="navitem"><a href="/#/login" use:active>{$t('Login')}</a></li>
                        </svelte:fragment>
                    </Authguard>
                </ul>
            <!-- </nav> -->
            <svelte:fragment slot="trail">
                <select bind:value={$locale} on:change={onChangeLocale}>
                    {#each locales as l}
                        <option value={l}>{l}</option>
                    {/each}
                </select>
                <Authguard>
                    <svelte:fragment slot="authed">
                            <p><Avatar width={'w-12'} src={$user.user_img} /></p>
                            <p>{$user.first_name + ' ' + $user.last_name}</p>
                    </svelte:fragment>
                </Authguard>
            </svelte:fragment>
        </AppBar>
    </svelte:fragment>
    <svelte:fragment slot="sidebarLeft">
        <SideMenus />
    </svelte:fragment>
    <svelte:fragment slot="sidebarRight">
        <p>{$t('Right Sidebar')}</p>
    </svelte:fragment>
    <svelte:fragment slot="pageFooter">
        <p class="text-center">{$t('Loaded from')} { url ? url : 'root' } @ Laravel + Vite + Svelte + Tailwind CSS &copy; 2023</p>
    </svelte:fragment>
    <!-- DEFAULT SLOT -->
    <Route path="/" redirect="/home"/>
    <Route path="/home"><h1>{$t("It is the main page")}</h1></Route>
    <Route path="/shared"><h1>{$t("It is a shared page")}</h1></Route>
    <Authguard>
        <div slot="authed">
            <Route path="/login/*" redirect="/role/Author"/>
            <Route path="/profile">
                <UserProfile />
            </Route>
            {#each userRoles as role}
                <Route path="/role/{role}">{ $t('role_content',{ role: $t(role) }) }</Route> <!-- TRANSLATE STRING WITH {{ VARIABLE }} -->
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
    <Route fallback><p>{$t('No page found')}</p></Route>
</AppShell>
<style>
    /* li:has(a.active) { @apply variant-filled-primary; }
    a.active {
        @apply btn variant-filled-primary btn-base text-primary-50;
    } */
    .navitem {
        @apply inline-block; /* 'token' means 'from theme' */
    }
    .navitem a {
        @apply btn variant-ghost-primary hover:variant-ghost-surface btn-base;
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

