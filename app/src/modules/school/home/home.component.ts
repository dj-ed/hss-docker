import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { SchoolService } from '../school.service';
import { School } from '../../../models/school.model';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import * as _ from 'lodash';

@Component({
    templateUrl: './home.component.html',
    styleUrls: ['./home.component.scss']
})
export class HomeComponent implements OnInit {

    school: School;
    sports$: BehaviorSubject<any[]> = new BehaviorSubject(undefined);
    poweredBy$: BehaviorSubject<boolean> = new BehaviorSubject(undefined);
    ready: boolean = false;

    constructor(public seoService: SeoService, public schoolService: SchoolService) {
    }

    ngOnInit(): void {
        this.schoolService.subscribeSchool()
            .map(school => {
                this.school = school;
                this.seoService
                    .setTitle(`${school.name} School - Home, ${school.stateName}`)
                    .setDescription('School Page');

                this.schoolService.loadSchoolSports(school.id)
                    .subscribe(response => {
                        this.sports$.next(response.sports);
                        this.poweredBy$.next(response.poweredBy);
                    });
            })
            .concatMap(() => {
                return this.sports$.filter(isReady => !_.isUndefined(isReady));
            })
            .subscribe(() => {
                this.ready = true;
            });
    }

}
