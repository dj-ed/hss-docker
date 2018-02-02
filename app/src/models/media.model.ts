import { Base } from './base.model';
import { NewsMedia } from "./news-media.model";

export class Media extends Base {

    comments: number;
    descriptions: string;
    id: number;
    isIframe: boolean;
    likes: number;
    mediaUrl: NewsMedia[];
    mediaType: string;
    players;
    title: string;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);

    }
}