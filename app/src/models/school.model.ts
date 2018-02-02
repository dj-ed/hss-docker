import { Base } from './base.model';
import { SchoolPerson } from './school-person.module';
import { SchoolSocial } from './school-social.module';

export class School extends Base {
    id: number;
    name: string;
    shortName: string;
    logoUrl: string;
    seasonGamePts: string;
    stateName: string;
    city: string;
    address: string;
    zip: string;
    socials: SchoolSocial;
    mainColor: string;
    secondColor: string;
    persons: SchoolPerson[];
    phone: string;
    phoneExt: string;
    fax: string;
    faxExt: string;
    mascot: string;

    constructor(itemData?) {
        super();
        super.loadFields(itemData);
    }
}
