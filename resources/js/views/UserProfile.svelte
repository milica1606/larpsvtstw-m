<script>
    import {user} from '../auth.js';
    let profile;
    $: profile = $user;
</script>
<h1>It is User's profile page</h1>
<span>{profile.last_name} {profile.first_name}</span> <img alt="user" src={profile.user_img} />
