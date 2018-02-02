import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { Team } from '../../../models/team.model';
import { TeamService } from '../team.service';

@Component({
    templateUrl: './live.component.html',
    styleUrls: ['../../../styles/live-stream.scss'],
    encapsulation: ViewEncapsulation.None,
})
export class LiveComponent implements OnInit {
    team: Team;

    constructor(public seoService: SeoService, public teamService: TeamService) {
    }

    ngOnInit(): void {
        this.teamService.subscribeTeam().subscribe(team => {
            this.team = new Team(team);

            this.seoService
                .setTitle('Team Live')
                .setDescription('Team Page');
        });
    }

}
