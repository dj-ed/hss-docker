import { Base } from './base.model';
import { Team } from './team.model';
import { Sport } from './sport.model';

export class Game extends Base {
    id: number;
    date: string;
    dateTime: string;
    gameType: string;
    where: string;
    team: Team;
    win: number;
    opponentTeam: Team;
    scoreTeam: number;
    scoreOpponent: number;
    sportId: number;
    sport: Sport;
    gamePosition: number;
    count: number;
    location: string;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }

    dateObj() {
        let fullDate = this.date;
        if (this.dateTime) {
            fullDate += ' ' + this.dateTime;
        }
        return new Date(fullDate);
    }

    isGameRecap() {
        const date = this.dateObj();
        return date.getTime() + 90 * 60 * 1000 < new Date().getTime();

    }

    isFutureGame() {
        const date = this.dateObj();
        return date.getTime() + 90 * 60 * 1000 > new Date().getTime();
    }

    inGameTime() {
        return this.dateObj().getHours() < 22 && this.dateObj().getHours() > 7;
    }

    getFullType() {
        let str;
        switch (this.gameType) {
            case 'D':
                str = 'district';
                break;
            case 'T':
                str = 'tournament';
                break;
            default:
                str = 'non district';
        }
        return str;
    }
}
