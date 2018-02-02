import {Component, Input, OnInit, ViewChild} from '@angular/core';
import { StatsService } from '../../../services/stats.service';
import { Game } from '../../../../models/game.model';
import * as _ from 'lodash';
import { RootService } from "../../../../modules/root/root.service";
import { Animations } from './score-board.animation';
import { SubscActionDirective } from "../../../directives/subsc-action.directive";

@Component({
    selector: 'score-board',
    templateUrl: './score-board.component.html',
    styleUrls: ['./score-board.component.scss', '../../../../styles/main.scss'],
    animations: [
        Animations.animeTrigger
    ],

})
export class ScoreBoardComponent implements OnInit {
    @Input('section') section: string;
    @ViewChild(SubscActionDirective) globalFavorites: SubscActionDirective;
    dividedGames = [];
    showId: number;
    gameDetails: object;
    page: number = 1;
    remainderCount: number;
    loadingActive: boolean = false;
    isSelectMode = false;
    searchText = '';

    constructor(public statsService: StatsService, public rootService: RootService) {
    }

    ngOnInit() {
        this.loadGames();

    }

    divideGames(games) {
        if (games) {
            _.chain(games).chunk(10).map((v) => {
                this.dividedGames.push({
                    games: v,
                    headerDates: this.getDateInterval(v)
                });
            }).value();
        }
        this.searchText = '';
        if (this.globalFavorites) {
            this.globalFavorites.isActiveSelect = false;
            this.globalFavorites.pushAllFavorites.emit([]);
        }
    }

    getDateInterval(games) {
        if (games.length > 1) {
            return {
                begin: games[0].dateObj(),
                end: games[games.length - 1].dateObj()
            };
        } else {
            return {
                begin: games[0].dateObj(),
            };
        }
    }

    loadGames() {
        this.loadingActive = true;
        this.statsService.loadScoreboard(this.page, this.section).subscribe(rez => {
            console.log(rez);
            const pagination = rez.meta.pagination;
            this.page = pagination.current_page + 1;
            this.remainderCount = pagination.total - pagination.current_page * pagination.per_page;
            const newGames = [];
            _.forEach(rez.data, v => {
                const newGame = new Game(v);
                newGames.push(newGame);
            });

            this.divideGames(newGames);
            this.loadingActive = false;
        });
    }

    showDeatail(id) {
        if (this.showId === id) {
            this.showId = undefined;
            this.gameDetails = undefined;
        } else {
            this.statsService.loadGameDetail(id).subscribe((rez) => {
                this.showId = id;
                this.gameDetails = rez;
            });
        }
    }

}
