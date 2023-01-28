// import './bootstrap';

import App from "./App.svelte";

const app = new App({
    target: document.getElementById("app"),
    props: {
        url: (<HTMLInputElement>document.getElementById("url")).value
    }
});

export default app;
