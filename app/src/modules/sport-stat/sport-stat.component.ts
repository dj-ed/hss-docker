import { Component, ViewEncapsulation } from '@angular/core';
import { RootService } from '../root/root.service';

@Component({
    templateUrl: './sport-stat.component.html',
    styleUrls: ['../../styles/stats.scss'],
    encapsulation: ViewEncapsulation.None,
})
export class SportStatComponent {

    constructor(public rootService: RootService) {
    }

}
