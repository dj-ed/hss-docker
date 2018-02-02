import { Injectable } from '@angular/core';
import 'rxjs/add/operator/map';
import 'rxjs/add/operator/filter';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { AjaxService } from '../../+shared/services/ajax.service';
import { RootService } from '../root/root.service';
import * as _ from 'lodash';

@Injectable()
export class PlayerService {
    playerCommon$: BehaviorSubject<any> = new BehaviorSubject(undefined);

    constructor(public ajaxService: AjaxService, public rootService: RootService) {
    }

    subscribePlayer() {
        return this.rootService.ready$
            .filter(isReady => isReady)
            .concatMap(() => {
                return this.playerCommon$.filter(player => !_.isUndefined(player));
            });
    }

    loadPlayerCommon(playerId) {
        this.playerCommon$.next(undefined);

        this.ajaxService.post('player', {playerId}).subscribe(response => {
            this.playerCommon$.next(response);
        });
    }

    loadMoreInfo(playerId) {
        return this.ajaxService.post('player/about', {playerId});
    }

    loadPlayerStats(playerId) {
        return this.ajaxService.post('player/about-stats', {playerId, sportId: this.rootService.currentSportId});
    }

    loadPlayerStandings(playerId, viewType) {
        return this.ajaxService.post('player/player-standings', {playerId, viewType});
    }

    searchInPlayerStandings({searchText, favorite, playerId, viewType}) {
        return this.ajaxService.post('player/player-standings', {searchText, favorite, playerId, viewType});
    }

    loadPSByPage(playerId, viewType, page) {
        return this.ajaxService.post('player/player-standings-page', {playerId, viewType, page});
    }

}