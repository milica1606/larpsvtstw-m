import type { RoleUserPivot } from "./roleUserPivot.type";
export interface Role {
    id: number,
    name: string,
    pivot: RoleUserPivot,
    permissions?: string[]
};
