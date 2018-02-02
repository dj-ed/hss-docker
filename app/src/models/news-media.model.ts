import { Base } from './base.model';

export class NewsMedia extends Base {
    id: number;
    title: string;
    source: string;
    size: string;
    location: string;
    type: string;
    isCover: boolean;
    fileUrl: string;
    thumbUrl: string;
    sortOrder: number;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
