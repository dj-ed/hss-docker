import { Base } from './base.model';

export class Varsity extends Base {
    id: string;
    name: string;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
