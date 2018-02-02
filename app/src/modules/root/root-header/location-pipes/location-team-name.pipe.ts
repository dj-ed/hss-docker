import { Pipe, PipeTransform } from '@angular/core';
import * as _ from 'lodash';

@Pipe({
    name: 'SortPipeTeamsNames'
})
export class SortPipeTeamsNames implements PipeTransform {
    transform(value: any, find?: any, sort?: any) {

        value.forEach((item)=>{
            item.searchName = item.schoolName.toLowerCase().substr(0,4);
        });

        let bip = _.sortBy(value, 'searchName');

        if (find !== undefined) {
            let valueNew = bip.filter(item => {
                return item.teamName.toLowerCase().search(find.toLowerCase()) !== -1;
            });
            let v = _.groupBy(valueNew, 'searchName');
            let a = _.chain(v).sortBy('searchName').value();

            return a;
        }else{
            let v =  _.groupBy(bip, 'searchName');
            let a = _.chain(v).sortBy('searchName').value();

            return a;
        }
    }
}