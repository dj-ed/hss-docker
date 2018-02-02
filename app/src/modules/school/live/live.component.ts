import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { SchoolService } from '../school.service';
import { School } from '../../../models/school.model';

@Component({
    templateUrl: './live.component.html',
    styleUrls: ['../../../styles/live-stream.scss',
                './live.component.scss'],
    encapsulation: ViewEncapsulation.None,
})
export class LiveComponent implements OnInit {
    school: School;

    constructor(public seoService: SeoService, public schoolService: SchoolService) {
    }

    ngOnInit(): void {
        this.schoolService.subscribeSchool().subscribe(school => {
            this.school = school;

            this.seoService
                .setTitle('School Live')
                .setDescription('School Page');
        });
    }

}
