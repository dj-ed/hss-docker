import { Component, Input, OnInit } from '@angular/core';
import { StatsService } from '../../../services/stats.service';

@Component({
    selector: 'team-standings',
    templateUrl: './team-standings.component.html',
    styleUrls: ['./team-standings.component.scss']
})
export class TeamStandingsComponent implements OnInit {
    teams = [];
    columns;
    columnsName;
    @Input() type;
    isSelectMode;
    searchText;

    constructor(public statsService: StatsService) {
    }

    ngOnInit(): void {
        this.loadTeamStandings();
    }

    loadTeamStandings() {
        this.statsService.loadStandings(this.type).subscribe(rez => {
            this.teams = rez.teams;
            this.columns = rez.columns;
            this.columnsName = rez.columnsName;
        });
    }
}
