import { Component, OnDestroy, OnInit } from '@angular/core';
import { StatsService } from '../../../../+shared/services/stats.service';
import { SportService } from "../../sport.service";
import * as _ from 'lodash';
import { Game } from "../../../../models/game.model";
import { Gender } from "../../../../models/gender.model";
import { Varsity } from "../../../../models/varsity.model";

@Component({
    templateUrl: './sport-scoreboard.component.html',
    selector: 'sport-scoreboard',
    styleUrls: ['./sport-scoreboard.component.scss'],

})
export class SportScoreboardComponent implements OnInit, OnDestroy {
    sportSubscriber;
    tables = [];

    constructor(public statsService: StatsService, public sportService: SportService) {
    }

    ngOnInit() {
        this.sportSubscriber = this.sportService.subscribeSport().subscribe(() => {
            this.loadGames();
        });
    }

    loadGames() {
        this.statsService.loadSportScoreboard().subscribe(rez => {
            this.tables = [];
            _.forEach(rez, (val) => {
                this.tables.push({
                    gender: new Gender(val.gender),
                    teamType: new Varsity(val.teamType),
                    games: _.map(val.games, (v) => {
                        return new Game(v);
                    })
                });
            });

        });
    }

    ngOnDestroy() {
        this.sportSubscriber.unsubscribe();
    }

}
