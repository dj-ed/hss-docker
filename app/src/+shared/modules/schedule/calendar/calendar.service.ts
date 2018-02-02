import { Injectable } from '@angular/core';
import { Game } from '../../../../models/game.model';
import { ScheduleService } from '../schedule.service';
import * as _ from 'lodash';

@Injectable()
export class CalendarService{
    games: Game[] = [];
    startDate: Date;
    endDate: Date;
    calendarType: string = 'week';
    moduleType: string;
    moduleTypeId: number;
    timeline: number;
    weekDate: Date = new Date();
    sportId;

    constructor(public scheduleService: ScheduleService) {
    }

    loadGames(sportId?) {
        this.scheduleService.getCalendarGames(
            {
                startDate: this.startDate.toDateString(),
                endDate: this.endDate.toDateString()
            },
            this.calendarType,
            this.moduleType,
            this.moduleTypeId,
            sportId).subscribe((rez) => {
            this.games = [];
            let newGame: Game;
            if (this.calendarType === 'week') {
                _.forEach(rez.data, v => {
                    newGame = new Game(v);
                    if (newGame.inGameTime()) {
                        this.getGamePosition(newGame);
                        this.games.push(newGame);
                    }
                });
            } else if (this.calendarType === 'month') {
                _.forEach(rez.data, v => {
                    newGame = new Game(v);
                    this.games.push(newGame);

                });
                this.games = _.chain(this.games).groupBy('date').value();
            } else {
                _.forEach(rez.data, v => {
                    newGame = new Game(v);
                    this.games.push(newGame);

                });
                this.games = _.keyBy(this.games, 'date');
            }
        });
    }

    getGamePosition(game: Game) {
        if (game.dateTime) {
            const date = game.dateObj();
            let gameMinutes;
            let timlineMinutes;
            gameMinutes = (date.getHours() - 8) * 60 + date.getMinutes();
            timlineMinutes = 18 * 60;
            const oneTimeCount = _.filter(this.games, (o) => {
                return o.dateTime === game.dateTime;
            }).length;
            game.gamePosition = Math.round((gameMinutes / timlineMinutes) * this.timeline) + 15 * oneTimeCount;
        }
        return 0;
    }

    toSqlFormat(date: Date) {
        return date.toISOString().substring(0, 10);
    }

    choseDate(date) {
        this.calendarType = 'week';
        this.weekDate = date;

    }
}
