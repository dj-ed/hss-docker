import { Component, Input, OnInit } from '@angular/core';
import { School } from '../../../models/school.model';
import { SchoolService } from '../school.service';
import { RootService } from '../../root/root.service';
import { Observable } from 'rxjs/Observable';
import * as _ from 'lodash';
import { ActivatedRoute } from '@angular/router';

@Component({
    selector: 'school-header',
    templateUrl: './school-header.component.html',
    styleUrls: ['./school-header.component.scss']
})
export class SchoolHeaderComponent implements OnInit {
    @Input() className?: string;
    school: School;
    prevSchoolId: number;
    nextSchoolId: number;
    openedSchoolList: boolean = false;
    activePage: string;

    constructor(public schoolService: SchoolService, public rootService: RootService,
                public activatedRoute: ActivatedRoute) {
    }

    ngOnInit(): void {
        Observable.fromEvent(document, 'click').subscribe((event: any) => {
            // close school list
            if (!event.target.closest('.school-switch')) {
                this.openedSchoolList = false;
            }
        });

        // update active page
        this.activatedRoute.url.subscribe(url => {
            this.activePage = ''; // home
            if (url.length) {
                this.activePage = url[0].path;
            }
        });

        this.schoolService.subscribeSchool().subscribe(school => {
            this.school = school;
            const index = _.findIndex(this.rootService.schoolList, ['id', school.id]);
            const nextIndex = (this.rootService.schoolList[index + 1]) ? index + 1 : 0;
            const prevIndex = (this.rootService.schoolList[index - 1]) ? index - 1 : this.rootService.schoolList.length - 1;
            this.nextSchoolId = this.rootService.schoolList[nextIndex].id;
            this.prevSchoolId = this.rootService.schoolList[prevIndex].id;
        });
    }

    toggleSchoolList() {
        this.openedSchoolList = !this.openedSchoolList;
    }

    getSchoolLink(type) {
        const rez = ['/school', (type === 'next') ? this.nextSchoolId : this.prevSchoolId, this.activePage];
        if (this.activePage === 'schedule') {
            const sportParams = this.activatedRoute.snapshot.params.sport;
            if (sportParams) {
                rez.push(sportParams);
            }
        }
        return rez;

    }

}
