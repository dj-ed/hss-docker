import { Component, OnDestroy, OnInit } from '@angular/core';
import { StatsService } from '../../../../+shared/services/stats.service';
import { SportService } from "../../sport.service";
import * as _ from 'lodash';
import { RootService } from "../../../root/root.service";

@Component({
    templateUrl: './sport-leaderboard.component.html',
    selector: 'sport-leaderboard',
    styleUrls: ['./sport-leaderboard.component.scss'],

})
export class SportLeaderboardComponent implements OnInit, OnDestroy {
    sportSubscriber;
    statistics = [];
    teamTypes = [];
    indexActiveTab = 0;

    constructor(public rootService: RootService, public statsService: StatsService, public sportService: SportService) {
    }

    ngOnInit() {
        this.sportSubscriber = this.sportService.subscribeSport().subscribe(() => {
            this.loadPlayers();
        });
    }

    loadPlayers(type?) {
        this.statsService.loadSportLeaderboard(type).subscribe(rez => {
            this.statistics = [];
            if (rez.teams) {
                this.teamTypes = rez.teams;
            }
            _.forEach(rez.stats, v => {
                this.statistics.push({
                    statName: v.statName,
                    stats: _.orderBy(v.stats, ['points'], 'desc'),
                });
            });
        });
    }

    ngOnDestroy() {
        this.sportSubscriber.unsubscribe();
    }

}
