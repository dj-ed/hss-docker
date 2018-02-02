import { PipeTransform, Pipe } from '@angular/core';
import {AllTeamsSchoolsService} from "../../../+shared/services/all-teams-schools.service";

@Pipe({name: 'itemsByChar'})
export class ItemsByCharPipe implements PipeTransform {

    constructor(public allSchoolsTeamsService: AllTeamsSchoolsService) {

    }

    transform(data, key, char) {
        return  this.allSchoolsTeamsService.getItemsByChar(data, key, char);
    }
}
