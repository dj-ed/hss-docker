import { Component, OnInit, Renderer2, ViewEncapsulation } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { TeamService } from './team.service';
import { Team } from '../../models/team.model';
import { RootService } from '../root/root.service';
import * as _ from 'lodash';

@Component({
    templateUrl: './team.component.html',
    styleUrls: ['../../styles/team.scss'],
    encapsulation: ViewEncapsulation.None
})
export class TeamComponent implements OnInit {
    team: Team;
    teams;
    nextTeamId: number;
    prevTeamId: number;
    openedSchoolList: boolean = false;
    ready: boolean = false;

    constructor(public route: ActivatedRoute, public teamService: TeamService, private renderer: Renderer2,
                public rootService: RootService) {
    }

    ngOnInit(): void {
        this.route.params.subscribe(params => {
            this.teamService.loadTeamCommon(+params['id']);
        });

        this.teamService.subscribeTeam().subscribe(team => {
            this.team = new Team(team);
            this.teams = team.teams.data;

            const index = _.findIndex(this.teams, ['id', team.id]);
            const nextIndex = (this.teams[index + 1]) ? index + 1 : 0;
            const prevIndex = (this.teams[index - 1]) ? index - 1 : this.teams.length - 1;
            this.nextTeamId = this.teams[nextIndex].id;
            this.prevTeamId = this.teams[prevIndex].id;

            this.ready = true;
        });
    }

    toggleSchoolList() {
        this.openedSchoolList = !this.openedSchoolList;
    }

    onDeactivate() {
        this.renderer.setProperty(document.body, 'scrollTop', 0);
    }

}
