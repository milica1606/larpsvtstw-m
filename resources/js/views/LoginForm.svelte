<script lang="ts">
    import {user} from '../auth.js';
    import { toastStore } from '@skeletonlabs/skeleton';
    import type { ToastSettings } from '@skeletonlabs/skeleton';

    import { t } from '../i18n/index';

    let email: string;
    let password: string;
    let formdata: {
        password?: string; email?: string;
    };

    $: formdata = { email, password };

    function log() {
        // $user = true;
        /* axios.post('http://localhost:8000/api/login', formdata)
            .then((response) => {
                console.log(response.data);
                $user = response.data.data;
            }); */
        fetch('http://localhost:8000/api/login', { method: 'POST', body: JSON.stringify(formdata), headers: {
            'Content-Type': 'application/json' }, redirect: 'error' })
        .then((response) => {
            if (response.status === 200) {
                return response.json();
            } else if(response.status === 401) {
                throw new Error(response.statusText);
            } else {
                throw new Error(response.statusText + ': Something went wrong on API server!');
            }
        })
        .then((data) => {
            console.log(data);
            $user = data.user;
            const ts: ToastSettings = {
                message: $t('Logged in successfully'),
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
            const ts: ToastSettings = {
                message: error,
                // Optional: Presets for primary | secondary | tertiary | warning
                preset: 'error',
                // Optional: The auto-hide settings
                autohide: true,
                timeout: 5000,
                // Optional: Adds a custom action button
                /* action: {
                    label: 'Login Error',
                    response: () => alert(error)
                } */
	        };
            toastStore.trigger(ts);
        });
    }
</script>
<main>
    <h1>{ $t('Please sign in') }</h1>
    <form>
        <label for="email">E-mail:</label><input class="field" id="email" type="text" placeholder={$t('Enter a valid e-mail address')} bind:value={email}/>
        <label for="password">{$t('Password')}:</label><input class="field" id="password" type="password" bind:value={password}/>
        <button class="btn variant-filled-primary btn-base" on:click|preventDefault={log}>{$t('Sign In')}</button>
    </form>
</main>

