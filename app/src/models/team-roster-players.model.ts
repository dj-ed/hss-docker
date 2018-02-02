import { Base } from './base.model';
export class TeamRosterPlayers extends Base {
    id: number;
    name: string;
    userPhotoUrl: string;
    number: string;
    positions: string[];
    height: string;
    height_in: string;
    weight: string;
    stats: {
        innerColumns: string[],
        innerColumnsName: string[],
        data: [{ innerColumns: any[] }]
    };
    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
