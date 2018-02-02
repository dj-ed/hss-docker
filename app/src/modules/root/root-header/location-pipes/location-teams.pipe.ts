import { Pipe, PipeTransform } from '@angular/core';
import * as _ from 'lodash';

@Pipe({
    name: 'SortPipeTeams'
})
export class SortPipeTeams implements PipeTransform {
    transform(value: any, find?: string, filter?: any) {
        if (find !== undefined) {
            let valueNew = value.filter(item => {
                return item.schoolName.toLowerCase().search(find.toLowerCase()) !== -1;
            });
            return _.uniqBy(valueNew.map(item => {
                return (item = {
                    name: item.genderName.toLowerCase() + ' ' + item.varsityName.toLowerCase(),
                    gender: item.genderName.toLowerCase(),
                    varsity: item.varsityName.toLowerCase()
                })
            }), 'name');
        }else{
            return _.uniqBy(value.map(item => {
                return (item = {
                    name: item.genderName.toLowerCase() + ' ' + item.varsityName.toLowerCase(),
                    gender: item.genderName.toLowerCase(),
                    varsity: item.varsityName.toLowerCase()
                })
            }), 'name');
        }
    }
}