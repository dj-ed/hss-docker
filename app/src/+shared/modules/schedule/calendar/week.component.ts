import { Component, OnDestroy, OnInit, ViewChild } from '@angular/core';
import { DatePipe } from '@angular/common';
import { CalendarService } from './calendar.service';
import { SchoolService } from '../../../../modules/school/school.service';
import { TeamService } from '../../../../modules/team/team.service';
import { RootService } from "../../../../modules/root/root.service";

@Component({
    selector: 'week',
    templateUrl: './week.component.html',
    styleUrls: ['./year-week-month.component.scss'],
    providers: [DatePipe],
})
export class WeekComponent implements OnInit, OnDestroy {
    week: Date[] = [];
    gameTime = [];
    @ViewChild('timeline') timeline;
    sportSubsrciber$;
    typeSubscriber$;

    constructor(private datePipe: DatePipe,
                public calendarService: CalendarService,
                public schoolService: SchoolService,
                public teamService: TeamService,
                public rootService: RootService) {
    }

    ngOnInit() {
        this.getGameTime();
        this.getWeek(this.calendarService.weekDate.getTime());
        this.calendarService.timeline = this.timeline.nativeElement.offsetWidth;
        // load games on init component
        if (this.calendarService.moduleType) {
            this.typeSubscriber$ = (this.calendarService.moduleType === 'school') ? this.schoolService.schoolCommon$ : this.teamService.teamCommon$;
            this.typeSubscriber$ = this.typeSubscriber$.subscribe(response => {
                if (response) {
                    this.calendarService.moduleTypeId = response.id;
                    this.calendarService.loadGames();
                }
            });
        } else {
            this.calendarService.loadGames();
        }
        // load games on change sport
        if (this.calendarService.moduleType === 'school') {
            this.sportSubsrciber$ = this.rootService.sportChange$.filter(sport => sport).subscribe(() => {
                this.calendarService.loadGames();
            });
        }
    }

    getWeek(seconds: number) {
        const date = new Date(seconds);
        this.week = [];
        const firstday = new Date(date.setDate(date.getDate() - date.getDay()));
        let d;
        for (d = 0; d <= 6; d++) {
            const weekDay = new Date(firstday).setDate(firstday.getDate() + d);
            this.week.push(new Date(weekDay));
        }
        this.calendarService.startDate = this.getStartDate();
        this.calendarService.endDate = this.getEndDate();

    }

    isWeekendDay(day: Date) {
        return day.getDay() === 0 || day.getDay() === 6;
    }

    change(changeType) {
        if (this.week.length) {
            if (changeType === 'next') {
                this.getWeek(this.week[0].setDate(this.week[0].getDate() + 7));
            } else if (changeType === 'prev') {
                this.getWeek(this.week[0].setDate(this.week[0].getDate() - 7));
            }
        }
    }

    getStartDate() {
        let rez;
        if (this.week.length) {
            rez = this.week[0];
        }
        return rez;

    }

    getEndDate() {
        let rez;
        if (this.week.length) {
            rez = this.week[this.week.length - 1];
        }
        return rez;
    }

    getGameTime() {
        let i;
        const endTime = 22;
        for (i = 8; i <= endTime; i++) {
            const time = new Date();
            time.setMinutes(0);
            time.setHours(i);
            this.gameTime.push(this.datePipe.transform(time, 'shortTime'));
        }
    }

    ngOnDestroy() {
        if (this.sportSubsrciber$) {
            this.sportSubsrciber$.unsubscribe();
        }
        if (this.typeSubscriber$) {
            this.typeSubscriber$.unsubscribe();
        }
    }

}
