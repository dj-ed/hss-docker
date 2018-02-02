import { Base } from './base.model';

export class NewsVideo extends Base {
    id: number;
    title: string;
    date: string;
    likes: number;
    comments: number;
    slug: string;
    source: string;
    authorName: string;
    description: string;
    videoUrl: string;
    videoType: string;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
