import { Component, Input, OnInit } from '@angular/core';
import { StatsService } from '../../../services/stats.service';
import * as _ from 'lodash';

@Component({
    selector: 'leader-board',
    templateUrl: './leader-board.component.html',
    styleUrls: ['./leader-board.component.scss', '../../../../styles/main.scss']
})
export class LeaderBoardComponent implements OnInit {
    leaderBoardSmallGrid = false;
    @Input('config') config?:any | {team: number, searchText: string, isSelectMode: boolean} = {};
    statistics = [];
    innerColumns = [];
    innerColumnsName = [];
    searchText;
    isSelectMode;

    gridState: boolean = true;

    constructor(public statsService: StatsService) {
    }

    ngOnInit() {
        this.statsService.laodLeaderboard(this.config.team).subscribe(rez => {
            _.forEach(rez.stats, v => {
                this.statistics.push({
                    statName: v.statName,
                    stats: _.orderBy(v.stats, ['points'], 'desc'),
                });
            });
            this.innerColumns = rez.innerColumns;
            this.innerColumnsName = rez.innerColumnsName;
        });
    }

    grider() {
        this.gridState = false;
        setTimeout(() => {
            this.gridState = true;
        }, 100);
    }

}
