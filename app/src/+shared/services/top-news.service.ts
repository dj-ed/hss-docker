import { Injectable } from '@angular/core';
import { AjaxService } from './ajax.service';
import { RootService } from '../../modules/root/root.service';
import { News } from '../../models/news.model';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import * as _ from 'lodash';
import { SportService } from '../../modules/sport/sport.service';

@Injectable()
export class TopNewsService {
    newsList: News[];
    ready$: BehaviorSubject<boolean> = new BehaviorSubject(false);

    constructor(public ajaxService: AjaxService, public rootService: RootService, public sportService: SportService) {
    }

    loadAllTopNews(sportPage?) {
        let subscriber;
        if (sportPage) {
            subscriber = this.sportService.subscribeSport().concatMap(rez => {
                return this.ajaxService.post('news/top-news', this.newsRequestParams());
            });
        } else {
            subscriber = this.ajaxService.post('news/top-news', this.newsRequestParams());
        }

        subscriber.subscribe(news => {
            this.newsList = [];
            _.forEach(news.data, item => {
                this.newsList.push(new News(item));
            });

            this.ready$.next(true);
        });
    }

    newsRequestParams() {
        const params: any = {};
        if (this.rootService.currentSportId) {
            params.sportId = this.rootService.currentSportId;
        }
        if (this.rootService.currentGenderId) {
            params.genderId = this.rootService.currentGenderId;
        }

        return params;
    }

    nextIndex(index) {
        return this.newsList[index + 1] ? index + 1 : 0;
    }

    prevIndex(index) {
        return this.newsList[index - 1] ? index - 1 : this.newsList.length - 1;
    }

    loadLatestNews() {
        return this.ajaxService.post('news/latest-top-news', this.newsRequestParams());
    }

}
