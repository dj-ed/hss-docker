import { Base } from './base.model';
import {TeamRosterPlayers} from "./team-roster-players.model";
export class TeamRoster extends Base {
    players: TeamRosterPlayers[];
    coaches: [{id, name, type, userPhotoUrl }];
    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}

