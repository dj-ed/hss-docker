import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { Team } from '../../../models/team.model';
import { TeamService } from '../team.service';

@Component({
    templateUrl: './team-stats.component.html',
    styleUrls: ['../../../styles/stats.scss'],
    encapsulation: ViewEncapsulation.None,
})
export class TeamStatsComponent implements OnInit {
    team: Team;

    constructor(public teamService: TeamService) {
    }

    ngOnInit(): void {
        this.teamService.subscribeTeam().subscribe(team => {
            this.team = new Team(team);

        });
    }
}
