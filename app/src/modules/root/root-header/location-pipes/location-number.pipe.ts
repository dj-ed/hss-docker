import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
    name: 'SortNumPipe'
})
export class SortNumPipe implements PipeTransform {
    transform(value: any, find?: string, type?: boolean) {

        if (type == true){
            if (find !== undefined) {
                return Array.from(new Set(
                    value.filter(item => {
                        return item.schoolName.toLowerCase().search(find.toLowerCase()) !== -1;
                    }).map(item => {
                        return item.schoolName.toLowerCase().substr(0 , 1);
                    })
                )).sort();
            } else {
                return Array.from(new Set(
                    value.map(item => {
                        return item.schoolName.toLowerCase().substr(0 , 1);
                    })
                )).sort();
            }
        }else{
            if (find !== undefined) {
                return Array.from(new Set(
                    value.filter(item => {
                        return item.name.toLowerCase().search(find.toLowerCase()) !== -1;
                    }).map(item => {
                        return item.name.toLowerCase().substr(0 , 1);
                    })
                )).sort();
            } else {
                return Array.from(new Set(
                    value.map(item => {
                        return item.name.toLowerCase().substr(0 , 1);
                    })
                )).sort();
            }
        }
    }
}