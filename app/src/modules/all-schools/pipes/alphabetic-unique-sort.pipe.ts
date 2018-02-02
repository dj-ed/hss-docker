import { PipeTransform, Pipe } from '@angular/core';
import {AllTeamsSchoolsService} from "../../../+shared/services/all-teams-schools.service";

@Pipe({name: 'alphabeticUniqueSort'})
export class AlphabeticUniqueSortPipe implements PipeTransform {
    config = {
      cities: 'countyName',
      schools: 'name'
    };
    constructor(public allTeamsSchoolsService: AllTeamsSchoolsService) {

    }

    transform(data, type) {
        let res;
        switch (type) {
            case 'cities' : res = this.allTeamsSchoolsService.sortUnique(data, this.config[type]); break;
            case 'schools' : res = this.allTeamsSchoolsService.sortUnique(data, this.config[type]); break;
        }
        return res;
    }
}
