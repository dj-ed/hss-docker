import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { TeamService } from '../team.service';
import { Team } from '../../../models/team.model';

@Component({
    templateUrl: './home-court.component.html',
    styleUrls: ['./home-court.component.scss'],
    encapsulation: ViewEncapsulation.None
})
export class HomeCourtComponent implements OnInit {
    team: Team;
    constructor(public seoService: SeoService, public teamService: TeamService) {
    }

    ngOnInit(): void {
        this.teamService.subscribeTeam().subscribe(team => {
            this.team = new Team(team);
            this.seoService
                .setTitle('Team Home')
                .setDescription('Team Page');
        });
    }
}
