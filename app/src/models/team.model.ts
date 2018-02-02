import { Base } from './base.model';
import { School } from './school.model';
import { TeamSocials } from './team-socials.model';

export class Team extends Base {
    id: number;
    name: string;
    shortName: string;
    teamTypeName: string;
    logoUrl: string;
    mainColor: string;
    teamSocials: TeamSocials;
    school: School;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
