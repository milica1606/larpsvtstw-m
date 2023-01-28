<script>
	import {user} from '../auth';
</script>

{#if $user.id}
	<slot name="authed"></slot>
{:else}
	<slot name="not_authed"></slot>
{/if}
