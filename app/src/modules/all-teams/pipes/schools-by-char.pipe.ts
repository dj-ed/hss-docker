import { PipeTransform, Pipe } from '@angular/core';
import {AllTeamsSchoolsService} from "../../../+shared/services/all-teams-schools.service";

@Pipe({name: 'schoolsByChar'})
export class SchoolsByCharPipe implements PipeTransform {

    constructor(public allSchoolsTeamsService: AllTeamsSchoolsService) {

    }

    transform(data, char) {
       return this.allSchoolsTeamsService.getItemsByChar(data, 'schoolName', char);
    }
}
