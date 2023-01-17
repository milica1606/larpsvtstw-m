<script>
    export const wholeScreen = true;
    export const responsiveHoriz = true;
    export const scrollPanels = true;
    export const navWidth = '8rem';
    import { setWidth } from "../actions/setWidth";
    let scrollLayout;
    $: scrollLayout = !scrollPanels;
</script>
<div class="layout" class:wholeScreen>
    <slot>
        {#if $$slots.header}
            <header>
                <slot name="header" />
            </header>
        {/if}
        <div class="main vertContainer" class:responsiveHoriz class:scrollLayout>
            <main class="main" class:scrollPanels>
                <slot name="main">Main content should be here</slot>
            </main>
            {#if $$slots.nav}
                <nav class="nav" class:scrollPanels use:setWidth={navWidth}>
                    <slot name="nav" />
                </nav>
            {/if}
            {#if $$slots.aside}
                <aside class:scrollPanels use:setWidth={navWidth}>
                    <slot name="aside" />
                </aside>
            {/if}
        </div>
        {#if $$slots.footer}
            <footer>
                <slot name="footer" />
            </footer>
        {/if}
    </slot>
</div>
<style>
    .layout {
        @apply flex flex-col;
    }
    .wholeScreen {
        @apply min-h-screen;
    }
    .main {
        @apply flex-1;
    }
    .vertContainer {
        @apply flex flex-col;
    }
    .responsiveHoriz {
        @apply  sm:flex-row;
    }
    .nav {
        @apply order-first;
    }
    .scrollPanels {
        @apply overflow-y-auto;
    }
    .scrollLayout {
        @apply overflow-y-hidden;
    }
</style>
