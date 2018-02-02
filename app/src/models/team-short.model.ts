import { Base } from './base.model';

export class TeamShort extends Base {
    id: number;
    name: string;
    shortName: string;
    logoUrl: string;
    seasonGamePts: string;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
