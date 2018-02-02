import { PipeTransform, Pipe } from '@angular/core';

@Pipe({name: 'getSelectedSchoolsBySport'})
export class GetSelectedSportPipe implements PipeTransform {

    constructor() {

    }

    transform(sports, sportId) {
        const found = sports.find(sport => sport.sportId === sportId);
        if (!found) {
            return []
        }
        if (found.schools) {
            return found.schools;
        } else {
            return sports;
        }
    }
}
