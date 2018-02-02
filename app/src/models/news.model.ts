import { Base } from './base.model';
import { NewsMedia } from './news-media.model';
import { NewsTag } from './news-tag.model';
import { NewsContributor } from './news-contributor.model';

export class News extends Base {
    id: number;
    title: string;
    date: string;
    source: string;
    text: string;
    likes: number;
    comments: number;
    sport: string;
    gender: string;
    slug: string;
    tags: NewsTag[];
    contributors: {
        data: NewsContributor[]
    };
    media: {
        data: NewsMedia[]
    };

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
