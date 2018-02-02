import { PipeTransform, Pipe } from '@angular/core';
import {AlphabeticUniqueSortPipe} from "./alphabetic-unique-sort.pipe";
import {ItemsByCharPipe} from "./items-by-char.pipe";


@Pipe({name: 'transformData'})
export class TransformDataPipe implements PipeTransform {

    constructor(public alphabeticUniqueSchoolsPipe: AlphabeticUniqueSortPipe, public itemsByChar: ItemsByCharPipe) {

    }

    transform(data, order) {
        switch (order) {
            case 'states': return data;
            case 'cities': return this.transformCities(data);
            case 'schools': return data;
        }
    }

    transformCities(data) {
        return this.alphabeticUniqueSchoolsPipe.transform(data, 'cities').map(char => {
                const foundCities = this.itemsByChar.transform(data, 'countyName', char);
                let countSchools = 0;
                foundCities.forEach(county => countSchools += county.count_schools);
                return {char, cities: foundCities, count: countSchools};
            });
    }
}
