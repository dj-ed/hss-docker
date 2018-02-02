import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { RootService } from '../../root/root.service';

@Component({
    templateUrl: './schedule.component.html',
    styleUrls: [
        '../../../styles/prefered-sport.scss',
        '../../../styles/schedule.scss'
    ],
    encapsulation: ViewEncapsulation.None,
})
export class ScheduleComponent implements OnInit {

    constructor(public seoService: SeoService, public rootService: RootService) {
    }

    ngOnInit(): void {
        this.seoService
            .setTitle('Sport')
            .setDescription('Sport Page');
    }
}
