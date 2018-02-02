import { Base } from './base.model';

export class Album extends Base {
    id: number;
    date: number;
    title: string;
    description: string;
    mediaType: string;
    countPhoto: number;
    countVideo: number;
    gameId: number|string;
    isIframe: boolean;
    index: string;
    likesCommentsCount: {likes: number, comments: number, mediaUrl};
    gameData: {
        gameType: string,
        opponentTeam: {
            logoUrl: string,
            name: string,
            shortName: string,
        },
        team: {
            id: number,
            logoUrl: string,
            name: string,
            shortName: string,
        },
        scoreOpponent: number,
        scoreTeam: number;
        where: string;
        win: number,
    };
    constructor(itemData) {
        super();
        super.loadFields(itemData);
    }
}