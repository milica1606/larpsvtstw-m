<script>
    import {user} from '../auth';
    import { Avatar } from '@skeletonlabs/skeleton';
    let profile;
    $: profile = $user;
</script>
<p>It is User's profile page</p>
<p class="font-bold">{profile.last_name} {profile.first_name}</p> <Avatar width={'w-48'} src={profile.user_img} />
