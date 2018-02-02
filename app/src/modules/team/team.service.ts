import { Injectable } from '@angular/core';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/filter';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { AjaxService } from '../../+shared/services/ajax.service';
import { RootService } from '../root/root.service';
import * as _ from 'lodash';

@Injectable()
export class TeamService {
    teamCommon$: BehaviorSubject<any> = new BehaviorSubject(undefined);

    constructor(public ajaxService: AjaxService, public rootService: RootService) {
    }

    subscribeTeam() {
        return this.rootService.ready$
            .filter(isReady => isReady)
            .concatMap(() => {
                return this.teamCommon$.filter(team => !_.isUndefined(team));
            });
    }

    loadTeamCommon(teamId) {
        this.teamCommon$.next(undefined);

        this.ajaxService.post('team', {teamId}).subscribe(response => {
            this.teamCommon$.next(response);
        });
    }

    loadGameRecap(gameId?) {
        return this.subscribeTeam().concatMap(team => {
            const data = {
                teamId: team.id
            };
            if (gameId) {
                data['gameId'] = gameId;
            }
            return this.ajaxService.post('team/game-recap', data);

        });

    }

    loadTeamRoster(teamId) {
        return this.rootService.ready$.filter(ready => ready).concatMap(() => {
            return this.ajaxService.post('team/roster', {teamId, sportId: this.rootService.currentSportId});
        });

    }

    gameRecapDetail(gameId) {
        return this.ajaxService.post('team/game-recap-detail', {gameId: gameId});
    }

    loadCoachCorner(teamId) {
        return this.ajaxService.post('team/coach-corner', {teamId});
    }
}
