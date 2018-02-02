import { Component, OnInit, ViewEncapsulation, ViewChild, ElementRef } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { ActivatedRoute, Router } from "@angular/router";
import { RootService } from "../../root/root.service";
import { DomSanitizer } from '@angular/platform-browser';
import * as _ from 'lodash';
import { ScrollLoadDirecrive } from "../../../+shared/directives/scroll-load.direcrive";
import { News } from "../../../models/news.model";
import { Observable } from "rxjs/Observable";
import { HeadlinesService } from "../../../+shared/services/headlines.service";

@Component({
    templateUrl: './headlines.component.html',
    styleUrls: ['../../../styles/headlines.scss']
})
export class HeadlinesComponent implements OnInit {
    @ViewChild('wrap') public wrap: ElementRef;
    @ViewChild(ScrollLoadDirecrive) public scrollLoad: ScrollLoadDirecrive;
    public dataRequest: any = {};
    public activePost: any;
    public activeComments: any;
    public activeSliders: any;
    public newsList: News[] = [];
    public scroll: any = {
        postPosition: [],
        current: {index: 0, percent: 0, endBottom: false, preTitle: false},
        bottom: null
    };
    public isLoadingBottom: boolean = false;
    public isPreviousNews: boolean = false;
    public bufferNewsList: News[] = [];
    public loadingPrevNews: boolean = false;

    constructor(public seoService: SeoService, public route: ActivatedRoute,
                public rootService: RootService, public sanitizer: DomSanitizer, public router: Router, public headlinesService: HeadlinesService) {
        if(this.rootService.isBrowser()) {
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }
        }
    }

    viewPreviousNews() {
        const newsListSegment = this.bufferNewsList.concat(this.newsList);
        this.loadingPrevNews = true;
        this.loadNews('prev', null, newsListSegment[0].slug)
            .subscribe(() => {
                this.loadingPrevNews = false;
                this.newsList = newsListSegment;
            });
    }

    loadNews(direction: string, slug?, firstSlug?): Observable<any> | any {
        this.dataRequest.direction = direction;
        if (direction === 'prev') {
            // first slug from newsList
            this.dataRequest.newsSlug = firstSlug ? firstSlug : this.newsList[0].slug;
            return this.headlinesService.loadNewsList(_.cloneDeep(this.dataRequest))
                .do((newsData) => {
                    this.bufferNewsList = newsData.data;
                    this.dataRequest.newsIds = newsData.newsIds;
                    this.bufferNewsList = newsData.data;
                    this.isPreviousNews = this.bufferNewsList.length > 0;
                });
        }
        if (direction === 'next') {
            // if slug didn't get - will be use first slug from newsList
            this.dataRequest.newsSlug = this.newsList.length > 0 ? this.newsList[this.newsList.length - 1].slug : slug;
            this.headlinesService.loadNewsList(_.cloneDeep(this.dataRequest))
                .do((newsData) => {
                    this.newsList = this.newsList.concat(newsData.data);
                    this.dataRequest.newsIds = newsData.newsIds;
                    delete this.dataRequest.firstLoad;
                    if (slug) {
                        this.loadNews('prev').subscribe(() => {
                        });
                    }
                }).subscribe((newsData) => {
                this.isLoadingBottom = true;
            });
        }
    }

    fixedPosition(slug: string, id: any) {
        window.scrollTo(0, _.find(this.scroll.postPosition, {slug}).begin);
        this.headlinesService.changeRoute(this.route, slug, this.dataRequest, id);
        this.scrollLoad.conversionScroll();
        this.scrollLoad.emitScrollData();
    }

    ngOnInit() {
        this.dataRequest.section = 'main';
        this.dataRequest.isHeadline = 1;
        if (this.dataRequest.section !== 'main') {
            delete this.dataRequest.isHeadline;
        }
        this.route.params.filter((params) => !_.find(this.newsList, {slug: params.view}))
            .subscribe((params: any) => {
                this.activeSliders = null;
                this.activePost = params.view;
                this.newsList = [];
                this.bufferNewsList = [];
                this.dataRequest.newsIds = null;
                this.dataRequest.tagName = params.tagName;
                this.dataRequest.tagType = params.tagType;
                this.dataRequest.firstLoad = true;
                this.isPreviousNews = false;
                this.isLoadingBottom = false;
                this.scrollLoad.refreshView();
                this.loadNews('next', params.view, true);
                window.scroll(0, 0);
            });
        if(this.rootService.isBrowser()) {
            window.scroll(0, 0);
        }

    }
}
