import { Injectable } from '@angular/core';
import { AjaxService } from "./ajax.service";
import { RootService } from "../../modules/root/root.service";
import { ActivatedRoute, Router } from "@angular/router";
import { NewsService } from "./news.service";
import { AppCookieService } from "./app-cookie.service";


@Injectable()
export class HeadlinesService {
    constructor(public ajaxService: AjaxService, public rootService: RootService, public router: Router, public newsService: NewsService, public cookie: AppCookieService) {
    }

    loadNewsList(dataRequest) {
        const url = (dataRequest.section === 'main' && !dataRequest.tagType) ? 'news/news-list-headlines' : dataRequest.tagType ? 'news/hot-tags' : 'news/news-list';
        return this.rootService.ready$.filter(ready => ready)
            .concatMap((ready: any) => {
                dataRequest.seasonId = this.rootService.currentSeasonId;
                dataRequest.sportId = this.rootService.currentSportId;
                dataRequest.genderId = this.rootService.currentGenderId;
                return this.ajaxService.post(url, dataRequest);
            });
    }

    changeRoute(route: ActivatedRoute, slug: string, savedDataForRequest, id?) {
        if (route.snapshot.paramMap.get('view') !== slug) {
            const queryParams: any = {view: slug};
            if (savedDataForRequest.tagType) {
                queryParams.tagType = savedDataForRequest.tagType;
            }
            if (savedDataForRequest.tagName) {
                queryParams.tagName = savedDataForRequest.tagName;
            }
            this.router.navigate(this.newsService.newsRoute(savedDataForRequest.section, savedDataForRequest.sectionId, queryParams));
            this.setReadedPost(id);
        }
    }

    setReadedPost(id) {
        let readedArray = this.cookie.get('readedPosts');
        if (readedArray) {
            readedArray = JSON.parse(readedArray);
            if (readedArray.indexOf(id) !== -1) {
                return;
            }
            readedArray.push(id);
            this.cookie.set('readedPosts', JSON.stringify(readedArray));
            this.ajaxService.post('reviews/post-views', {modelId: id, modelType: 'news'}).subscribe(() => {
            });
        } else {
            this.cookie.set('readedPosts', JSON.stringify([id]));
        }
    }

}