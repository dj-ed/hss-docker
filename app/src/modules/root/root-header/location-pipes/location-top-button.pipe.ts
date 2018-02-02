import { Pipe, PipeTransform } from '@angular/core';
import * as _ from 'lodash';

@Pipe({
    name: 'TopButtons'
})
export class TopButtons implements PipeTransform {
    transform(value: any, filters?: any) {

        if( filters != 'all' ){
            let valueNew = _.filter(value, item=>{
                return item.genderName.toLowerCase() == filters.gender;
            });
            return _.filter(valueNew, item=>{
                return item.varsityFullName.toLowerCase() == filters.varsity;
            });
        }else{
            return value;
        }
    }
}