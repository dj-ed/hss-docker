import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { Team } from '../../../models/team.model';
import { TeamService } from '../team.service';

@Component({
    templateUrl: './krossover-inner.component.html',
    styleUrls: ['../krossover/krossover.component.scss'],
})
export class KrossoverInnerComponent implements OnInit {
    team: Team;

    constructor(public seoService: SeoService, public teamService: TeamService) {
    }

    ngOnInit(): void {
        this.teamService.subscribeTeam().subscribe(team => {
            this.team = new Team(team);

            this.seoService
                .setTitle('Team Krossover Inner')
                .setDescription('Team Page');
        });
    }

}
