import { Base } from './base.model';

export class Season extends Base {
    id: number;
    title: string;
    titleShort: string; // URL key

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
