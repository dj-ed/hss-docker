import { Component, OnDestroy, OnInit } from '@angular/core';
import { StatsService } from '../../../../+shared/services/stats.service';
import { SportService } from "../../sport.service";

@Component({
    templateUrl: './sport-standings.component.html',
    selector: 'sport-standings',
    styleUrls: ['../../../../+shared/components/stats/full-board/full-board.component.scss', './sport-standings.component.scss'],

})
export class SportStandingsComponent implements OnInit, OnDestroy {
    sportSubscriber;
    teams = [];
    teamTypes = [];
    indexActiveTab = 0;



    constructor(public statsService: StatsService, public sportService: SportService) {
    }

    ngOnInit() {
        this.sportSubscriber = this.sportService.subscribeSport().subscribe(() => {
            this.loadTeams();
        });
    }

    loadTeams(type?) {
        this.statsService.loadSportTeamStandings(type).subscribe(rez => {
            this.teams = rez.teams;
            if (rez.teamTypes) {
                this.teamTypes = rez.teamTypes;
            }
        });

    }

    ngOnDestroy() {
        this.sportSubscriber.unsubscribe();
    }

}
