import { Base } from './base.model';
import { CoachShortArticle } from './coach-short-article.model';
export class CoachCorner extends Base {
    id: number;
    name: string;
    bio: string;
    userPhotoUrl: string;
    social: any;
    articles: {data: CoachShortArticle[]};
    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
