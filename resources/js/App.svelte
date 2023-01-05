<script lang="ts">
    import {Route} from 'tinro';
    import Contacts from './Contacts.svelte';
    export let name: string = "test";
</script>
<main>
    <h1>{ name } Laravel + Vite + Svelte + Tailwind CSS</h1>
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
</main>
