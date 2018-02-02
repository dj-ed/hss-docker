import { Component, OnInit } from '@angular/core';
import { Player } from '../../../models/player.model';
import { PlayerService } from '../player.service';

@Component({
    templateUrl: './player-stats.component.html'
})
export class PlayerStatsComponent implements OnInit {
    player: Player;

    constructor(public playerService: PlayerService) {
    }

    ngOnInit(): void {
        this.playerService.subscribePlayer().subscribe(player => {
            this.player = new Player(player);
        });
    }
}
