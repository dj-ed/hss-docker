import { Base } from './base.model';

export class NewsShort extends Base {
    id: number;
    title: string;
    date: number;
    likes: number;
    comments: number;
    sport: string;
    gender: string;
    slug: string;
    thumbUrl: string;
    source?: string;
    authorName?: string;
    description?: string;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }

}
