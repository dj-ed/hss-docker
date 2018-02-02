import { Base } from './base.model';

export class Gender extends Base {
    id: number;
    name: string;
    imgLogo: string;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
