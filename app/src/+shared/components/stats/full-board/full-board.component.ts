import { Component, Input, OnDestroy, OnInit } from '@angular/core';
import { StatsService } from '../../../services/stats.service';
import * as _ from 'lodash';

@Component({
    selector: 'full-board',
    templateUrl: './full-board.component.html',
    styleUrls: ['./full-board.component.scss']
})
export class FullBoardComponent implements OnInit, OnDestroy {
    @Input('config') config?: {team:number, searchText: string, isSelectMode: boolean}|any = {};
    page: number = 1;
    remainderCount: number;
    dividedTeams = [];
    subscriber;
    rand = 0;

    constructor(public statsService: StatsService) {
    }

    ngOnInit() {
        this.loadFullBoard();
    }

    loadFullBoard() {
        this.subscriber = this.statsService.loadFullBoardData(this.page).subscribe(rez => {
            this.page = rez.current_page + 1;
            this.remainderCount = rez.total - rez.current_page * rez.per_page;
            this.divideTeams(rez.data);
        });
    }

    divideTeams(teams) {
        if (teams) {
            _.chain(teams).chunk(10).map((v, index) => {

                this.dividedTeams.push({
                    teams: v,
                    pts: this.getPtsInterval(v),
                    open: (!this.config.team && index === 0) ? true : _.findKey(v, {team_id: this.config.team})
                });
            }).value();
        }
    }

    getPtsInterval(v) {
        let str;
        if (v.length) {
            str = +v[0].score_team;
            if (v.length > 1) {
                str += '-' + +v[v.length - 1].score_team;
            }
        }
        return str;
    }

    openGroup(index) {
        if (this.dividedTeams[index]['open']) {
            this.dividedTeams[index]['open'] = undefined;
        } else {
            this.dividedTeams[index]['open'] = true;
        }
        this.rand = Math.floor(Math.random() * (1000 - 1 + 1)) + 1;
    }

    ngOnDestroy() {
        this.subscriber.unsubscribe();
    }
}
