import { PipeTransform, Pipe } from '@angular/core';
import {AllTeamsSchoolsService} from "../../../+shared/services/all-teams-schools.service";

@Pipe({name: 'alphabeticSort'})
export class AlphabeticSortPipe implements PipeTransform {

    constructor(public allTeamsSchollsService: AllTeamsSchoolsService) {

    }

    transform(data: any, type, order): any {
        switch (type) {
            case 'states':
                return this.allTeamsSchollsService.sort(data, 'stateName', order);
            case 'cities':
                return this.sortCities(data, order);
            case 'schools':
                return this.sortSchools(data, order);
        }
    }

    sortCities(data, order) {
         return data.sort((city1, city2) => {
                if (city1.char === '#' || city2.char === '#') {
                    return this.allTeamsSchollsService.sortNumbers(city1.char, city2.char);
                } else {
                    return this.allTeamsSchollsService.sortStrings(city1.char, city2.char, order);
                }
            });
    }

    sortSchools(data, order) {
        return data.sort((charInfo1, charInfo2) => {
            if (charInfo1.char === '#' || charInfo2.char === '#') {
                return this.allTeamsSchollsService.sortNumbers(charInfo1.char, charInfo2.char);
            } else {
                return this.allTeamsSchollsService.sortStrings(charInfo1.char, charInfo2.char, order);
            }
        });
    }

}
