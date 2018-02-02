import { Base } from './base.model';

export class SimpleSelectOption extends Base {
    value: string;
    label?: string;
    className?: string;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
