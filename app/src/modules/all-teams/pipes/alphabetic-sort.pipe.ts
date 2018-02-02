import { PipeTransform, Pipe } from '@angular/core';
import {AllTeamsSchoolsService} from "../../../+shared/services/all-teams-schools.service";

@Pipe({name: 'alphabeticSort'})
export class AlphabeticSortPipe implements PipeTransform {

    constructor(public allTeamsSchoolsService: AllTeamsSchoolsService) {

    }

    transform(data: any, type, order, layer?): any {
        switch (type) {
            case 'states':
               return this.allTeamsSchoolsService.sort(data, 'stateName', order);
            case 'cities':
                return this.allTeamsSchoolsService.sort(data, 'countyName', order);
            case 'schools':
                return this.sortSchools(data, order, layer);
        }
    }


    sortSchools(data, type, layer) {
        if (layer === 'global') {
            return data.sort((school1Arr, school2Arr) => {
                if (school1Arr[0].char === '#' || school2Arr[0].char === '#') {
                    return this.allTeamsSchoolsService.sortNumbers(school1Arr[0].char, school2Arr[0].char);
                } else {
                    return this.allTeamsSchoolsService.sortStrings(school1Arr[0].char, school2Arr[0].char, type);
                }
            });
        } else if (layer === 'local') {
            return data.sort((school1, school2) => {
                if (Number.isInteger(+school1.schoolName[0].toLowerCase()) || Number.isInteger(+school2.schoolName[0].toLowerCase()) ) {
                    return this.allTeamsSchoolsService.sortNumbers(school1.schoolName, school2.schoolName);
                } else {
                    return this.allTeamsSchoolsService.sortStrings(school1.schoolName, school2.schoolName, type);
                }
            });
            }
        }

}
