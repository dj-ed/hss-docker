import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { StatsService } from "../../../+shared/services/stats.service";
import { ActivatedRoute } from "@angular/router";
import { RootService } from "../../root/root.service";

@Component({
    templateUrl: './team-standings.component.html',
    styleUrls: ['../../../+shared/components/stats/team-standings/team-standings.component.scss'],
})
export class TeamStandingsComponent implements OnInit {
    teams: object;
    columns;
    columnsName;
    activeTab = 0;
    searchText;
    isSelectMode;

    constructor(public seoService: SeoService, public rootService: RootService, router: ActivatedRoute, public statsService: StatsService) {
    }

    ngOnInit(): void {
        this.seoService
            .setTitle('Sport')
            .setDescription('Sport Page');
        this.loadTeamStandings();

    }

    loadTeamStandings() {
        this.statsService.loadStandings().subscribe(rez => {
            this.teams = rez.teams;
            this.columns = rez.columns;
            this.columnsName = rez.columnsName;
        });
    }

}
