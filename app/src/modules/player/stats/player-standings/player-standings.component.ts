import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../../+shared/services/seo.service';
import { Player } from '../../../../models/player.model';
import { PlayerService } from '../../player.service';
import * as _ from 'lodash';
import { Animations } from './player-standings.animation';

@Component({
    templateUrl: './player-standings.component.html',
    styleUrls: ['./player-standings.component.scss'],
    animations: [
        Animations.animeTrigger
    ],
})
export class PlayerStandingsComponent implements OnInit {
    player: Player;
    type: string = 'state';
    groups: object;
    columns: object;
    showMore: boolean;
    limit: number = 6;
    searchText;
    isSelectMode;

    constructor(public seoService: SeoService, public playerService: PlayerService) {
    }

    ngOnInit(): void {
        this.playerService.subscribePlayer().subscribe(player => {
            this.player = new Player(player);
            this.loadStandings();

            this.seoService
                .setTitle('Player Standings')
                .setDescription('Player Page');
        });
    }

    loadStandings() {
        this.playerService.loadPlayerStandings(this.player.id, this.type).subscribe((rez) => {
            this.groups = rez.pages;
            // update group limit if current player not in default limit group
            _.forEach(this.groups, (val, k) => {
                if (val.stats.length && k >= this.limit) {
                    this.limit = k + 1;
                }
            });
            //

            this.columns = rez.columns;
        });
    }

    changeType(type) {
        this.type = type;
        this.loadStandings();
    }

    openGroup(index, page) {
        if (!this.groups[index].stats.length) {
            this.playerService.loadPSByPage(this.player.id, this.type, page).subscribe((rez) => {
                this.groups[index]['stats'] = rez.stats;
            });
        } else {
            this.groups[index]['stats'] = [];
        }
    }


    loadSearchData() {
        if (!this.searchText && !this.isSelectMode) {
            this.loadStandings();
            return;
        }
       // this.playerService.searchInPlayerStandings({searchText: this.searchText, favorite: this.isSelectMode, playerId: this.player.id, viewType: this.type})
       //     .subscribe((data) => {

      //      });

    }

}
