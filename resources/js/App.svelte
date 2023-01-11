<script lang="ts">
    import {Route, active} from 'tinro';

    // import Contacts from './Contacts.svelte';
    import {user} from './auth.js';
   	import Authguard from './Authguard.svelte';
    import UserProfile from './views/UserProfile.svelte';
    import LoginForm from './views/LoginForm.svelte';
    //import axios from 'axios';
    //axios.defaults.withCredentials = true;
    let name = 'test';


    function logout() {
        // $user=false;
        fetch('http://localhost:8000/api/logout', { method: 'POST', body: JSON.stringify({}), headers: {
            'Content-Type': 'application/json' }})
        .then((response) => {
            if (response.status === 200) {
                return response.json();
            } else {
                throw new Error('Something went wrong on API server!');
            }
        })
        .then((data) => {
            console.log(data.message);
            if (data.message)
                $user = { id: 0 };
        })
        .catch((error) => {
            console.error(error);
        });
    }
</script>
<main>
    <h1>{ name } Laravel + Vite + Svelte + Tailwind CSS</h1>
    <!--<nav>
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
    <Route path="/contacts"><Contacts /></Route> -->
    <nav>
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


    <style>
        :global(.active){font-weight: bold; text-decoration: underline}
        a {outline : none}
    </style>
</main>
