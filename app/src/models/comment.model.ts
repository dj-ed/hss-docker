import { Base } from './base.model';

export class Comment extends Base {
    id: number;
    text: string;
    audioUrl: string;
    userName: string;
    userPhotoUrl: string;
    userId: number;
    createdAt: number;
    likes: number;
    isVoted: boolean;
    replyId: number;
    replies: Comment[] = [];

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
