import { Base } from './base.model';

export class Sport extends Base {
    id: number;
    title: string;
    logoUrl: string;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
