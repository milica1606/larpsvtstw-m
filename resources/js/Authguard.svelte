<script>
	import {user} from './auth.js';
</script>

{#if $user.id}
	<slot name="authed"></slot>
{:else}
	<slot name="not_authed"></slot>
{/if}
