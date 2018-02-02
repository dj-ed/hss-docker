import { PipeTransform, Pipe } from '@angular/core';
import * as _ from 'lodash';

@Pipe({name: 'reverse'})
export class ReversePipe implements PipeTransform {

    transform(input: any): any {
        const reverse = [];
        _.forEach(input, item => {
            reverse.unshift(item);
        });

        return reverse;
    }
}
