import {writable, type Writable} from 'svelte/store';
import type { User } from './types/user.type';
const uid = {id: 0, roles:[]};
export const user: Writable<User> = writable(uid);
