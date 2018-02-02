import { Injectable } from '@angular/core';
import { AjaxService } from "../../services/ajax.service";
import { TeamService } from "../../../modules/team/team.service";
import { SchoolService } from "../../../modules/school/school.service";
import { PlayerService } from "../../../modules/player/player.service";
import { RootService } from "../../../modules/root/root.service";
import { Observable } from "rxjs/Observable";

@Injectable()
export class GalleryService {
    rootReady$;

    constructor(public ajaxService: AjaxService, public teamService: TeamService, public schoolService: SchoolService, public playerService: PlayerService, public rootService: RootService) {
        this.rootReady$ = this.rootService.ready$.filter(ready => ready);
    }

    loadAlbums(data) {
        const requesData = {};
        if (data.type !== 'sport') {
            let param;
            let service;
            switch (data.type) {
                case 'team':
                    param = 'teamId';
                    service = this.teamService.subscribeTeam();
                    break;
                case 'player':
                    param = 'playerId';
                    service = this.playerService.subscribePlayer();
                    break;
            }
            return service.concatMap((res) => {
                requesData[param] = res.id;
                return this.ajaxService.post('gallery/albums', requesData);
            });
        } else if (data.type === 'sport') {
            return this.rootReady$.concatMap(() => {
                return this.ajaxService.post('gallery/calendar', {page: data.page, seasonId: this.rootService.currentSeasonId, sportId: this.rootService.currentSportId});
            });
        }
    }

    loadOneGallery(type, albumId) {
        let service;
        switch (type) {
            case 'team':
                service = this.teamService.subscribeTeam();
                break;
            case 'player':
                service = this.playerService.subscribePlayer();
                break;
            default:
                service = Observable.of(null);
        }
        return service.concatMap((data) => {
            return this.ajaxService.post('gallery/view-album', {albumId: data ? data.albumId : albumId});
        }).map(res => {
            return res.data;
        });
    }
}