<script lang="ts">
    import {user} from '../auth.js';

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
            } else {
                throw new Error('Something went wrong on API server!');
            }
        })
        .then((data) => {
            console.log(data);
            $user = data.user;

        })
        .catch((error) => {
            console.error(error);
        });


    }
</script>
<h1>Please sign in</h1>
<form>
    <label for="email">E-mail:</label><input id="email" type="text" placeholder="Enter a valid e-mail address" bind:value={email}/>
    <label for="password">Password:</label><input id="password" type="password" bind:value={password}/>
    <button on:click|preventDefault={log}>Sign In</button>
</form>
