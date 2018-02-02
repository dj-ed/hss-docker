import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { Team } from '../../../models/team.model';
import { TeamService } from '../team.service';
import {TeamRoster} from "../../../models/team-roster.model";
import * as _ from 'lodash';

@Component({
    templateUrl: './roster.component.html',
    styleUrls: ['./roster.component.scss']
})
export class RosterComponent implements OnInit {
    team: Team;
    teamRoster: TeamRoster|any;
    isSelectMode;
    searchText;

    constructor(public seoService: SeoService, public teamService: TeamService) {
    }
    ngOnInit(): void {
        this.teamService.subscribeTeam().subscribe(team => {
            this.team = new Team(team);
            this.teamService.loadTeamRoster(this.team.id).subscribe((data) => {
                this.teamRoster = new TeamRoster(data);
                this.teamRoster.players = this.teamRoster.players.map(player => {
                    player.firstName = player.name.split(' ')[0];
                    player.lastName = player.name.split(' ')[1];
                    return player;
                });
            });
            this.seoService
                .setTitle('Team Roster')
                .setDescription('Team Page');
        });
    }
}
