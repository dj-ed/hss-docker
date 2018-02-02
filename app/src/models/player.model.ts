import { Base } from './base.model';
import { Team } from './team.model';
import { PlayerSocial } from './player-social.module';
import { PlayerList } from './player-list.model';

export class Player extends Base {
    id: number;
    userPhotoUrl: string;
    name: string;
    number: number;
    guard: string;
    guardShort: string;
    height: string;
    heightIn: string;
    weight: string;
    position: string;
    positionShort: string;
    social: PlayerSocial;
    teamPlayers: PlayerList[];
    team: Team;
    metrics: object;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
