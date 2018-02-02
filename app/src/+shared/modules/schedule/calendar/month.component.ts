import { Component, OnInit } from '@angular/core';
import { CalendarService } from './calendar.service';
import { SchoolService } from '../../../../modules/school/school.service';
import * as _ from 'lodash';
import { DatePipe } from '@angular/common';
import { TeamService } from '../../../../modules/team/team.service';
import { RootService } from "../../../../modules/root/root.service";

@Component({
    selector: 'month',
    styleUrls: ['./year-week-month.component.scss'],
    templateUrl: './month.component.html',
    providers: [DatePipe],

})
export class MonthComponent implements OnInit {
    month: Date[];
    dayOfWeek = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
    groupedMonth;
    chosenMonth: Date;

    constructor(public calendarService: CalendarService, public schoolService: SchoolService, public teamService: TeamService, public rootService: RootService) {
        this.chosenMonth = new Date();
    }

    ngOnInit() {
        this.getMonth();
        if (this.calendarService.moduleType) {
            const subscriber = (this.calendarService.moduleType === 'school') ? this.schoolService.schoolCommon$ : this.teamService.teamCommon$;
            subscriber.subscribe(response => {
                if (response) {
                    this.calendarService.moduleTypeId = response.id;
                    this.calendarService.loadGames();

                }
            });
        } else {
            this.calendarService.loadGames();

        }
    }

    getMonth() {
        const date = this.chosenMonth;
        this.month = [];
        const firstMonthDay = new Date(date.getFullYear(), date.getMonth(), 1);
        const firstDay = new Date(firstMonthDay.getFullYear(), firstMonthDay.getMonth(), firstMonthDay.getDate() - firstMonthDay.getDay());
        const lastMonthDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        const diference = Math.ceil((lastMonthDay.getTime() - firstDay.getTime()) / (1000 * 3600 * 24));
        let d;
        for (d = 0; d <= diference || d % 7 !== 0; d++) {
            const monthDay = new Date(firstDay).setDate(firstDay.getDate() + d);
            this.month.push(new Date(monthDay));
        }
        //
        this.groupMonthByWeeks();
        //
        this.calendarService.startDate = this.getStartDate();
        this.calendarService.endDate = this.getEndDate();
    }

    getStartDate() {
        let rez;
        if (this.month.length) {
            rez = this.month[0];
        }
        return rez;

    }

    getEndDate() {
        let rez;
        if (this.month.length) {
            rez = this.month[this.month.length - 1];
        }
        return rez;
    }

    change(changeType) {
        if (this.month.length) {
            if (changeType === 'next') {
                this.chosenMonth = new Date(this.chosenMonth.getFullYear(), this.chosenMonth.getMonth() + 1);
                this.getMonth();
            } else if (changeType === 'prev') {
                this.chosenMonth = new Date(this.chosenMonth.getFullYear(), this.chosenMonth.getMonth() - 1);
                this.getMonth();
            }
        }
    }

    isWeekendDay(day: Date) {
        return day.getDay() === 0 || day.getDay() === 6;
    }

    groupMonthByWeeks() {
        this.groupedMonth = _.chain(this.month).map((val, key) => {
            const weekK = Math.floor(key / 7);
            return {
                key: weekK,
                data: val,
            };
        }).groupBy('key').toArray().value();
    }

    getGames(date, day) {
        return day['games'] = this.calendarService.games[date];
    }

}
