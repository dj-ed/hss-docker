import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../../+shared/services/seo.service';
import { Player } from '../../../../models/player.model';
import { PlayerService } from '../../player.service';

@Component({
    templateUrl: './team-standings.component.html',
    styleUrls: ['./team-standings.component.scss']
})
export class TeamStandingsComponent implements OnInit {
    player: Player;
    isSelectMode;
    searchText;

    constructor(public seoService: SeoService, public playerService: PlayerService) {
    }


    ngOnInit(): void {
        this.playerService.subscribePlayer().subscribe(player => {
            this.player = new Player(player);

            this.seoService
                .setTitle('Player Stand')
                .setDescription('Sport Page');
        });
    }
}
