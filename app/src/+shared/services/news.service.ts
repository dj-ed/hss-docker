import { Injectable } from '@angular/core';
import { RootService } from "../../modules/root/root.service";
import { UserService } from "../services/user.service";
import { AjaxService } from "./ajax.service";
import { SportService } from "../../modules/sport/sport.service";
import { PlayerService } from "../../modules/player/player.service";
import { TeamService } from "../../modules/team/team.service";
import { SchoolService } from "../../modules/school/school.service";
import { Observable } from "rxjs/Observable";


@Injectable()
export class NewsService {

    constructor(public rootService: RootService, public userService: UserService, public ajaxService: AjaxService,
                public sportService: SportService,
                public playerService: PlayerService,
                public teamService: TeamService,
                public schoolService: SchoolService) {
    }

    loadNews(dataRequest) {
        return this.ajaxService.post('news/news-by-id', dataRequest, false);
    }

    newsRoute(section: string, parentId?: any, queryParams?: any): any[] {
        if (!parentId) {
            return this.rootService.sportUrl('/sport/headlines', queryParams);
        } else {
            let path;
            let newsSection;
            if (section.match(/s$/i)) {
                section = section.substring(0, section.length - 1);
                newsSection = section !== 'player' ? 'headlines' : 'blog';
            }
            path = ['/' + section, parentId, newsSection];
            if (queryParams) {
                path.push(queryParams);
            }
            return path;
        }
    }

    loadDynamicHotTags(data) {
        return this.ajaxService.post('news/get-hot-tags', data);
    }

    loadLastVideoNews(sectionInfo) {
        let data = {};
        data['section'] = sectionInfo.type;
        return this.getSubscriber(sectionInfo.type).map(model => {
            data = Object.assign(data, this.compactRootData());
            data['sectionId'] = model.id;
            return this.ajaxService.post('news/last-video-news', data);
        }).concatAll();
    }

    loadLatestNews(sectionInfo) {
        let data = {};
        data['section'] = sectionInfo.type;
        return this.getSubscriber(sectionInfo.type).map(model => {
            data = Object.assign(data, this.compactRootData());
            data['sectionId'] = model.id;
            return this.ajaxService.post('news/latest-news', data).map(responce => responce.data);
        }).concatAll();
    }

    loadPopularNews(type) {
        let data = {};
        data['isHeadline'] = type === 'main' ? 1 : 0;

        return this.getSubscriber(type).map(model => {
            data = Object.assign(data, this.compactRootData());
            return this.ajaxService.post('news/most-popular', data).map(responce => responce.data);
        }).concatAll();

    }

    compactRootData() {
        return {
            genderId: this.rootService.currentGenderId,
            seasonId: this.rootService.currentSeasonId,
            sportId: this.rootService.currentSportId,
        };
    }

    getSubscriber(section): Observable<any> {
        let typeSubscriber;
        switch (section) {
            case 'main':
                typeSubscriber = this.rootService.ready$.filter(isReady => isReady);
                break;
            case 'sport':
                typeSubscriber = this.sportService.subscribeSport();
                break;
            case 'players':
                typeSubscriber = this.playerService.subscribePlayer();
                break;
            case 'teams':
                typeSubscriber = this.teamService.subscribeTeam();
                break;
            case 'schools':
                typeSubscriber = this.schoolService.subscribeSchool();
                break;
        }
        return typeSubscriber;
    }

}