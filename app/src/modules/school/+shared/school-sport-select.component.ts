import { Component } from '@angular/core';
import { RootService } from "../../root/root.service";
import { SchoolService } from "../school.service";
import { ScheduleService } from "../../../+shared/modules/schedule/schedule.service";
import { CalendarService } from "../../../+shared/modules/schedule/calendar/calendar.service";
import { ActivatedRoute } from "@angular/router";
import { Router } from '@angular/router';
@Component({
    selector: 'school-sport-select',
    templateUrl: './school-sport-select.component.html',
    styleUrls: ['./school-sport-select.component.scss']
})
export class SchoolSportSelectComponent {
    public readyRoot;
    public routeParams: any;

    constructor(public rootService: RootService, public schoolService: SchoolService, public scleduleService: ScheduleService, public calendarService: CalendarService,
                public route: ActivatedRoute, public router: Router) {
        this.readyRoot = this.rootService.ready$.filter(ready => ready);
        this.route.parent.params.subscribe(params => {
            this.routeParams = Object.assign({}, params);
        });
    }

    isActive(instruction: any[]) {
        return this.router.isActive(this.router.createUrlTree(instruction), true);

    }
}