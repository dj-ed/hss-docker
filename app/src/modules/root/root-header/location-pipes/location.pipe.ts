import { Pipe, PipeTransform } from '@angular/core';

@Pipe({
    name: 'SortPipe'
})
export class SortPipe implements PipeTransform {
    transform(value: any, find?: any) {
        if (find !== undefined) {
            return value.filter(item => {
                return item.name.toLowerCase().search(find.toLowerCase()) !== -1;
            }).sort(function(a, b) {
                const nameA = a.name.toLowerCase();
                const nameB = b.name.toLowerCase();
                if (nameA < nameB) {
                    return -1;
                }
                if (nameA > nameB) {
                    return 1;
                }
                return 0;
            });
        } else {
            return value.sort(function(a, b) {
                const nameA = a.name.toLowerCase();
                const nameB = b.name.toLowerCase();
                if (nameA < nameB) {
                    return -1;
                }
                if (nameA > nameB) {
                    return 1;
                }
                return 0;
            });
        }
    }
}