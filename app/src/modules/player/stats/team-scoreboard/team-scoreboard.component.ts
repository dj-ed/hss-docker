import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../../+shared/services/seo.service';
import { Player } from '../../../../models/player.model';
import { PlayerService } from '../../player.service';

@Component({
    templateUrl: './team-scoreboard.component.html',
    styleUrls: ['./team-scoreboard.component.scss']
})
export class TeamScoreboardComponent implements OnInit {
    player: Player;

    constructor(public seoService: SeoService, public playerService: PlayerService) {
    }

    ngOnInit(): void {
        this.playerService.subscribePlayer().subscribe(player => {
            this.player = new Player(player);

            this.seoService
                .setTitle('Player Score')
                .setDescription('Player Page');
        });
    }
}
