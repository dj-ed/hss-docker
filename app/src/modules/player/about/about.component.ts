import { Component, OnDestroy, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { Player } from '../../../models/player.model';
import { PlayerService } from '../player.service';
import { RootService } from "../../root/root.service";

@Component({
    templateUrl: './about.component.html',
    styleUrls: ['./about.component.scss']
})
export class AboutComponent implements OnInit, OnDestroy {
    player: Player;
    metrics: object;
    summary: object;
    playerMetricsShower = false;
    showMoreCourt = false;
    isMore = false;
    playerSubscriber$;
    personalResult: object;
    seasonStats: object;

    constructor(public seoService: SeoService, public playerService: PlayerService, public rootService: RootService) {
    }

    ngOnInit(): void {
        this.playerSubscriber$ = this.playerService.subscribePlayer().subscribe(player => {
            this.player = new Player(player);
            this.playerService.loadMoreInfo(this.player.id).subscribe((rez) => {
                this.metrics = rez.metrics;
                this.summary = rez.summary;
                if (Object.keys(this.summary['offTheCourt'].data).length > 6) {
                    this.isMore = true;
                }
            });

            this.playerService.loadPlayerStats(this.player.id).subscribe((rez) => {
                this.personalResult = rez.personalResult;
                this.seasonStats = rez.seasonStats;
            });
            this.seoService
                .setTitle('Player About')
                .setDescription('Player Page');
        });
    }

    ngOnDestroy() {
        this.playerSubscriber$.unsubscribe();
    }

}
