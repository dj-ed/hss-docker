import { Component, OnInit, Input, ViewChild, PLATFORM_ID, Inject, OnDestroy } from '@angular/core';
import { ScheduleService } from '../schedule.service';
import { Game } from '../../../../models/game.model';
import * as _ from 'lodash';
import { isPlatformBrowser } from '@angular/common';
import { SchoolService } from '../../../../modules/school/school.service';
import { TeamService } from "../../../../modules/team/team.service";
import { ActivatedRoute } from "@angular/router";
import { RootService } from "../../../../modules/root/root.service";
import { Observable } from "rxjs/Observable";

@Component({
    selector: 'today-component',
    templateUrl: './today.component.html',
    styleUrls: ['./today.component.scss']
})
export class TodayComponent implements OnInit, OnDestroy {
    @Input() type: string;
    @Input() sportId;
    typeId: number;

    @ViewChild('timeline') timeline;
    @ViewChild('nightTimline') nightTimline;
    games: Game[] = [];
    selectedDate: Date;
    todayDate: Date;
    updateTime$;
    sportSubsriber$;
    typeSubscriber$;

    constructor(public scheduleService: ScheduleService, @Inject(PLATFORM_ID) private platformId: object, public schoolService: SchoolService, public teamService: TeamService,
                public route: ActivatedRoute, public rootService: RootService) {
    }

    ngOnInit() {
        this.todayDate = new Date();
        this.selectedDate = new Date();
        this.timeline = this.timeline.nativeElement.offsetWidth;
        this.nightTimline = this.nightTimline.nativeElement.offsetWidth;
        // load games on init component
        if (this.type) {
            this.typeSubscriber$ = (this.type === 'school') ? this.schoolService.schoolCommon$ : this.teamService.teamCommon$;
            this.typeSubscriber$ = this.typeSubscriber$.filter(rez => rez).takeUntil(() => this).subscribe(response => {
                if (response) {
                    this.typeId = response.id;
                    this.loadGames();

                }
            });
        } else {
            this.loadGames();
        }

        if (this.type === 'school') {
            // load games on change sport
            if (this.type === 'school') {
                this.sportSubsriber$ = this.rootService.sportChange$.filter(sport => sport).subscribe(() => {
                    this.loadGames();
                });
            }
        }
        // update date
        if (isPlatformBrowser(this.platformId)) {
            this.updateTime$ = Observable.interval(1000).subscribe(() => {
                this.todayDate = new Date();
            });
        }
    }

    loadGames() {

        this.scheduleService.getDayGames(this.selectedDate.toDateString(), this.type, this.typeId).subscribe((rez: any) => {
            this.games = [];
            _.forEach(rez.data, v => {
                const newGame = new Game(v);
                if (this.inGameTime(newGame.dateObj())) {
                    this.getGamePosition(newGame);
                    this.games.push(newGame);
                }
            });
        });
    }

    getGamePosition(game: Game) {
        if (game.dateTime) {
            const date = game.dateObj();
            let gameMinutes;
            let timlineMinutes;
            const oneTimeCount = _.filter(this.games, (o) => {
                return o.dateTime === game.dateTime;
            }).length;
            gameMinutes = (date.getHours() - 8) * 60 + date.getMinutes();
            timlineMinutes = 14 * 60;
            game.gamePosition = Math.round((gameMinutes / timlineMinutes) * this.timeline) + 15 * oneTimeCount;
        }
        return 0;
    }

    getTimeRunnerPosition() {
        // in game time
        if (this.todayDate.getDate() === this.selectedDate.getDate() && this.inGameTime()) {
            let minutes;
            let timlineMinutes;
            minutes = (this.todayDate.getHours() - 8) * 60 + this.todayDate.getMinutes();
            timlineMinutes = 14 * 60;
            return Math.round((minutes / timlineMinutes) * this.timeline);

        } else if (this.todayDate.getDate() === this.selectedDate.getDate() && (this.todayDate.getHours() >= 22 || this.todayDate.getHours() < 2)) {
            // not in game time
            let hours;
            let minutes;
            switch (this.todayDate.getHours()) {
                case 23:
                    hours = 1;
                    break;
                case 0:
                    hours = 2;
                    break;
                case 1:
                    hours = 3;
                    break;
            }
            minutes = this.todayDate.getMinutes() + 60 * hours;

            return Math.round((minutes / (4 * 60)) * this.nightTimline);
        }

        return false;
    }

    inGameTime(datObj?) {
        if (!datObj) {
            datObj = this.todayDate;
        }
        return datObj.getHours() < 22 && datObj.getHours() > 7;
    }

    nextDay() {
        this.selectedDate = new Date(this.selectedDate.getFullYear(), this.selectedDate.getMonth(), this.selectedDate.getDate() + 1);
        this.loadGames();
    }

    prevDay() {
        const newDate = new Date(this.selectedDate.getFullYear(), this.selectedDate.getMonth(), this.selectedDate.getDate() - 1);
        this.selectedDate = newDate;
        this.loadGames();
    }

    ngOnDestroy() {
        if (this.sportSubsriber$) {
            this.sportSubsriber$.unsubscribe();

        }
        if (this.updateTime$) {
            this.updateTime$.unsubscribe();
        }

        if (this.typeSubscriber$) {
            this.typeSubscriber$.unsubscribe();
        }

    }

}
