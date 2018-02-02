import { Base } from './base.model';

export class User extends Base {
    id: number;
    firstName: string;
    lastName: string;
    photoUrl: string;
    units: string;
    role: string;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
