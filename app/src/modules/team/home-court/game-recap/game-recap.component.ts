import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Params } from '@angular/router';
import { TeamService } from '../../team.service';
import { Game } from '../../../../models/game.model';
import * as _ from 'lodash';
import { DomSanitizer } from '@angular/platform-browser';
import { RootService } from '../../../root/root.service';
import { Animations } from './game-recap.animation';

@Component({
    templateUrl: './game-recap.component.html',
    styleUrls: ['./game-recap.component.scss'],
    selector: 'game-recap',
    animations: [
        Animations.animeTrigger
    ],
})
export class GameRecapComponent implements OnInit {
    currentGame: Game;
    nextGame: Game;
    prevGame: Game;
    gameMedia: object;
    recapDetail: boolean;
    scoreboard: object;
    topGamePlayers: object;
    topPlayers: object;
    tabActive: string = 'main';
    newsId: number;

    detailPlayers: boolean = true;

    constructor(private route: ActivatedRoute, private teamService: TeamService, public sanitizer: DomSanitizer, public rootService: RootService) {
    }

    ngOnInit(): void {
        this.route.params.subscribe((params: Params) => {

            this.teamService.loadGameRecap(params['view']).take(1).subscribe((response) => {
                if (response.games) {
                    this.clearDetail();
                    _.forEach(response.games, (v, k) => {
                        if (v) {
                            this[k] = new Game(v);
                        } else {
                            this[k] = undefined;
                        }
                    });
                }
                if (response.gameMedia) {
                    this.gameMedia = response.gameMedia;
                    if (this.gameMedia['type'] === 'embed') {
                        this.gameMedia['embedHtml'] = this.getSecureEmbed();
                    }
                }
            });
        });
    }

    grider() {
        this.detailPlayers = false;
        setTimeout(() => {
            this.detailPlayers = true;
        }, 100);
    }

    getSecureEmbed() {
        return this.sanitizer.bypassSecurityTrustHtml('<iframe class="inner-media"  ' +
            'allowfullscreen frameborder="0" controls src="' + this.gameMedia['link'] + '?rel=0">' +
            '</iframe>');
    }

    showRecapDetail() {
        if (this.recapDetail === true) {
            this.clearDetail();
        } else {
            this.teamService.gameRecapDetail(this.currentGame.id).subscribe(rez => {
                this.scoreboard = rez.scoreboard;
                this.topGamePlayers = rez.topGamePlayers;
                this.topPlayers = rez.topPlayers;
                this.newsId = rez.newsId;
                this.recapDetail = true;
            });
        }
    }

    clearDetail() {
        this.recapDetail = false;
        this.scoreboard = undefined;
        this.topGamePlayers = undefined;
        this.topPlayers = undefined;
        this.newsId = undefined;
    }
}
