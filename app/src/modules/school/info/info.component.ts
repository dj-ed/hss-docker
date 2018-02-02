import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { SchoolService } from '../school.service';
import { School } from '../../../models/school.model';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { DomSanitizer, SafeHtml } from '@angular/platform-browser';
import * as _ from 'lodash';
import {environment} from '../../../../.env/environment';

@Component({
    templateUrl: './info.component.html',
    styleUrls: ['./info.component.scss']
})
export class InfoComponent implements OnInit {
    description$: BehaviorSubject<string> = new BehaviorSubject(undefined);
    otherPersons$: BehaviorSubject<any[]> = new BehaviorSubject(undefined);
    contactPersons$: BehaviorSubject<any[]> = new BehaviorSubject(undefined);
    school: School;
    ready: boolean = false;
    mapHtml: SafeHtml;

    constructor(public seoService: SeoService, public schoolService: SchoolService, public sanitizer: DomSanitizer) {
    }

    ngOnInit(): void {

        this.schoolService.subscribeSchool()
            .map(school => {
                this.school = school;

                this.seoService
                    .setTitle(`${school.name} School - Info, ${school.stateName}`)
                    .setDescription('School Info');

                this.mapHtml = this.sanitizer.bypassSecurityTrustHtml('<iframe frameborder="0" style="border:0" allowfullscreen '
                    + 'src = "https://www.google.com/maps/embed/v1/place?key=' + environment.GOOGLE_MAP_API_KEY
                    + '&q=' + this.school.address + ', ' + this.school.city + ' ' + this.school.stateName
                    + ' ' + this.school.zip + '"></iframe>');

                this.schoolService.loadSchoolInfo(school.id)
                    .subscribe(response => {
                        this.description$.next(response.description);
                        this.contactPersons$.next(response.contactPersons);
                        this.otherPersons$.next(response.otherPersons);
                    });
            })
            .concatMap(() => {
                return this.description$.filter(isReady => !_.isUndefined(isReady));
            })
            .subscribe(() => {
                this.ready = true;
            });
    }

}
