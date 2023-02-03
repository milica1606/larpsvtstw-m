import type { Role } from "./role.type";
export interface User {
    id: number,
    first_name?: string,
    last_name?: string,
    email?: string,
    user_img?: string,
    roles?: Role[],
};
