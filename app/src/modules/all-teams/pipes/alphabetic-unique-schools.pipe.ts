import { PipeTransform, Pipe } from '@angular/core';
import {AllTeamsSchoolsService} from "../../../+shared/services/all-teams-schools.service";

@Pipe({name: 'alphabeticUniqueSchools'})
export class AlphabeticUniqueSchoolsPipe implements PipeTransform {

    constructor(public allTeamsSchoolsService: AllTeamsSchoolsService) {

    }

    transform(data) {
       return this.allTeamsSchoolsService.sortUnique(data, 'schoolName');
    }
}
