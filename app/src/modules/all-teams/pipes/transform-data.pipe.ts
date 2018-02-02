import { PipeTransform, Pipe } from '@angular/core';
import {SchoolsByCharPipe} from "./schools-by-char.pipe";
import {AlphabeticUniqueSchoolsPipe} from "./alphabetic-unique-schools.pipe";

@Pipe({name: 'transformData'})
export class TransformDataPipe implements PipeTransform {

    constructor(public alphabeticUniqueSchoolsPipe: AlphabeticUniqueSchoolsPipe, public schoolsByCharPipe: SchoolsByCharPipe) {

    }

    transform(data, order) {
       switch (order) {
           case 'states': return data;
           case 'cities': return data;
           case 'schools': return this.transformSchools(data);
       }
    }

    transformSchools(data) {
        const sortByCharSchools = [];
        this.alphabeticUniqueSchoolsPipe.transform(data).forEach((char, index) => {
            const schools = this.schoolsByCharPipe.transform(data, char).map((school) => {
                school.char = char;
                return school;
            });
            sortByCharSchools.push(schools);
        });
        return sortByCharSchools;
    }
}
