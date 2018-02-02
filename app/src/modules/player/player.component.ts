import { Component, OnInit, Renderer2, ViewEncapsulation } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { PlayerService } from './player.service';
import { Player } from '../../models/player.model';
import * as _ from 'lodash';
import { RootService } from "../root/root.service";

@Component({
    templateUrl: './player.component.html',
    styleUrls: [
        '../../styles/schedule.scss',
        '../../styles/stats.scss',
        '../../styles/team.scss'
    ],
    encapsulation: ViewEncapsulation.None
})
export class PlayerComponent implements OnInit {

    player: Player;
    playerSubscriber$;
    nextPlayerId: number;
    prevPlayerId: number;
    openedPlayerList: boolean = false;
    shortFirstName: string;
    playerNum: string;

    constructor(public route: ActivatedRoute, public playerService: PlayerService, private renderer: Renderer2, public rootService: RootService) {
    }

    ngOnInit(): void {
        this.route.params.subscribe(params => {
            this.playerService.loadPlayerCommon(+params['id']);
        });

        this.playerSubscriber$ = this.playerService.subscribePlayer().subscribe(player => {
            this.player = new Player(player);

            const index = _.findIndex(this.player.teamPlayers, ['id', this.player.id]);
            const nextIndex = (this.player.teamPlayers[index + 1]) ? index + 1 : 0;
            const prevIndex = (this.player.teamPlayers[index - 1]) ? index - 1 : this.player.teamPlayers.length - 1;
            this.nextPlayerId = this.player.teamPlayers[nextIndex].id;
            this.prevPlayerId = this.player.teamPlayers[prevIndex].id;

            this.shortFirstName = this.player.name.split(' ')[0].charAt(0) + '. ' + this.player.name.split(' ')[1];
            this.playerNum = '#' + this.player.number;
        });
    }

    onDeactivate() {
        this.renderer.setProperty(document.body, 'scrollTop', 0);
    }

    togglePlayerList() {
        this.openedPlayerList = !this.openedPlayerList;
    }

}
