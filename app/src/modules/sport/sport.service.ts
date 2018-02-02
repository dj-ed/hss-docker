import { Injectable } from '@angular/core';
import { AjaxService } from '../../+shared/services/ajax.service';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { RootService } from '../root/root.service';
import { Sport } from '../../models/sport.model';
import * as _ from 'lodash';

@Injectable()
export class SportService {
    sport$: BehaviorSubject<Sport> = new BehaviorSubject(undefined);
    constructor(public ajaxService: AjaxService, public rootService: RootService) {
    }

    loadSport(sportTitle) {

        this.sport$.next(undefined);
        this.rootService.ready$
            .filter(isReady => isReady)
            .subscribe(() => {
                _.forEach(this.rootService.sportList, sport => {
                    if (sport.title.toLowerCase() === sportTitle.toLowerCase()) {
                        this.sport$.next(sport);
                    }
                });
            });
    }

    subscribeSport() {
        return this.sport$.filter(sport => !_.isUndefined(sport));
    }

    loadSportTeams(sportId) {
        const seasonId = this.rootService.currentSeasonId;
        const genderId = this.rootService.currentGenderId;
        return this.ajaxService.post('all-teams/short-list', {sportId, seasonId, genderId});
    }

}
