import { Component, Inject, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { ActivatedRoute } from '@angular/router';
import { TopNewsService } from '../../../+shared/services/top-news.service';
import { News } from '../../../models/news.model';
import { RootService } from '../../root/root.service';
import { NewsShort } from '../../../models/news.short.model';
import { PageScrollConfig, PageScrollInstance, PageScrollService } from 'ng2-page-scroll';
import { DOCUMENT } from '@angular/platform-browser';
import 'rxjs/add/operator/scan';
import * as _ from 'lodash';

@Component({
    templateUrl: './top-news.component.html',
    styleUrls: ['../../../+shared/components/top-news/top-news.component.scss', './top-news.component.scss']
})
export class TopNewsComponent implements OnInit {
    latestNews: NewsShort[];
    currentNews: News;
    nextNews: News;
    prevNews: News;

    constructor(public seoService: SeoService, public topNewsService: TopNewsService, public route: ActivatedRoute,
                public rootService: RootService, private pageScrollService: PageScrollService, @Inject(DOCUMENT) private document: any) {
    }

    ngOnInit(): void {
        this.topNewsService.loadAllTopNews();

        this.topNewsService.ready$.filter(isReady => isReady).concatMap(() => {
            return this.route.params;
        }).subscribe(params => {
            let currentIndex = 0;
            if (params['view']) {
                currentIndex = _.findIndex(this.topNewsService.newsList, ['slug', params['view']]);
            }

            const nextIndex = this.topNewsService.nextIndex(currentIndex);
            const prevIndex = this.topNewsService.prevIndex(currentIndex);

            this.currentNews = this.topNewsService.newsList[currentIndex];
            this.prevNews = this.topNewsService.newsList[prevIndex];
            this.nextNews = this.topNewsService.newsList[nextIndex];

            this.seoService
                .setTitle(`Top News - ${this.currentNews.title}`)
                .setDescription('Sport Page');
        });

        this.topNewsService.loadLatestNews()
            .map(response => response.data)
            .subscribe(news => {
                this.latestNews = [];
                _.forEach(news, oneNews => {
                    this.latestNews.push(new NewsShort(oneNews));
                });
            });

        PageScrollConfig.defaultDuration = 500;
    }

    scrollTop() {
        const pageScrollInstance: PageScrollInstance = PageScrollInstance.simpleInstance(this.document, '#top-slider');
        this.pageScrollService.start(pageScrollInstance);
    }

}
