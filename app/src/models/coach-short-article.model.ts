import { Base } from './base.model';
export class CoachShortArticle extends Base {
    id: number;
    title: string;
    description: string;
    date: number;
    author: string;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}