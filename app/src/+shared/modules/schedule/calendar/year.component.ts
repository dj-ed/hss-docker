import { Component, OnInit } from '@angular/core';
import { CalendarService } from './calendar.service';
import { SchoolService } from '../../../../modules/school/school.service';
import * as _ from 'lodash';
import { TeamService } from '../../../../modules/team/team.service';

@Component({
    selector: 'year',
    styleUrls: ['./year-week-month.component.scss'],
    templateUrl: './year.component.html',
})

export class YearComponent implements OnInit {
    chosenYear: Date;
    dayOfWeek = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
    months = [];
    groupedMouths = [];

    constructor(public calendarService: CalendarService, public schoolService: SchoolService, public teamService: TeamService) {
        this.chosenYear = new Date();
    }

    ngOnInit() {
        this.getYear().grouped();
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

    change(changeType) {
        if (changeType === 'next') {
            this.chosenYear = new Date(this.chosenYear.getFullYear() + 1, 0);
        } else if (changeType === 'prev') {
            this.chosenYear = new Date(this.chosenYear.getFullYear() - 1, 0);
        }
        this.getYear().grouped();
    }

    getYear() {
        for (let i = 0; i < 12; i++) {
            this.getMonth(i);
        }
        this.calendarService.startDate = this.getStartDate();
        this.calendarService.endDate = this.getEndDate();
        return this;
    }

    getMonth(i) {
        this.months[i] = [];
        const firstMonthDay = new Date(this.chosenYear.getFullYear(), i, 1);
        const firstDay = new Date(firstMonthDay.getFullYear(), firstMonthDay.getMonth(), firstMonthDay.getDate() - firstMonthDay.getDay());
        let d;
        for (d = 0; d < 35 || d % 7 !== 0; d++) {
            const monthDay = new Date(firstDay).setDate(firstDay.getDate() + d);

            this.months[i].push({
                date: new Date(monthDay),
                inCurrentMonth: (firstMonthDay.getMonth() !== new Date(monthDay).getMonth())
            })
            ;

        }
    }

    grouped() {
        this.groupedMouths = _.chain(this.months).map((val, key) => {
            return _.chunk(val, 7);
        }).chunk(4).value();
        this.months = _.chunk(this.months, 4);
    }

    getDateClass(date) {
        const curGame = this.calendarService.games[date];
        if (curGame) {
            if (curGame.count > 1) {
                return 's';
            } else {
                return curGame.gameType.toLowerCase();
            }
        }
        return '';
    }

    getStartDate() {
        return new Date(this.chosenYear.getFullYear(), 0, 1);
    }

    getEndDate() {
        return new Date(this.chosenYear.getFullYear(), 11, 31);
    }


}
