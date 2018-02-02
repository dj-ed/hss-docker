import {Component, OnInit, Input, ViewChild, ElementRef} from '@angular/core';
import { DomSanitizer } from '@angular/platform-browser';
import {RootService} from "../../../../modules/root/root.service";
import {ActivatedRoute, Router} from "@angular/router";
import * as _ from 'lodash';
import {ScrollLoadDirecrive} from "../../../directives/scroll-load.direcrive";
import {News} from "../../../../models/news.model";
import {Observable} from "rxjs/Observable";
import { HeadlinesService } from "../../../services/headlines.service";
@Component({
    selector: 'news-list-stream',
    templateUrl: './news-list-stream.component.html',
    styleUrls: ['./news-list-stream.component.scss']
})
export class NewsListStreamComponent implements OnInit {
    @Input() type: string;
    @ViewChild('wrap') public wrap: ElementRef;
    @ViewChild(ScrollLoadDirecrive) public scrollLoad: ScrollLoadDirecrive;
    public activePost: any;
    public activeComments: any;
    public scroll = {
        postPosition : [],
        current : { index : 0, percent: 0, endBottom: false}
    };
    public dataRequest: any = {};
    public newsList: News[] = [];
    public isLoadingTop: boolean = false;
    public isLoadingBottom: boolean = false;
    public isPreviousNews: boolean = false;
    public bufferNewsList: News[] = [];
    public loadingPrevNews: boolean = false;

    constructor(public  sanitizer: DomSanitizer, public rootService: RootService,
                public route: ActivatedRoute, public router: Router, public headlinesService: HeadlinesService) {
        if (this.rootService.isBrowser()) {
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
    
    loadNews(direction: string, slug?, firstSlug?): Observable<any>|any {
        this.dataRequest.direction = direction;
        if (direction === 'prev') {
            // first slug from newsList
            this.dataRequest.newsSlug =  firstSlug ? firstSlug : this.newsList[0].slug;
            return this.headlinesService.loadNewsList(_.cloneDeep(this.dataRequest))
                .do((newsData) => {
                    this.bufferNewsList = newsData.data;
                    this.dataRequest.newsIds = newsData.newsIds;
                    this.bufferNewsList = newsData.data;
                    this.isPreviousNews = this.bufferNewsList.length > 0;
                });
        }
        if (direction === 'next') {
            // if slug haven't got - will be use first slug from newsList
            this.dataRequest.newsSlug = this.newsList.length > 0 ? this.newsList[this.newsList.length - 1].slug : slug;
            this.headlinesService.loadNewsList(_.cloneDeep(this.dataRequest))
                .do((newsData) => {
                    this.newsList = this.newsList.concat(newsData.data);
                    this.dataRequest.newsIds = newsData.newsIds;
                    delete this.dataRequest.firstLoad;
                    if (slug) {
                        this.loadNews('prev').subscribe(() => {});
                    }
                }).subscribe((newsData) => {
                this.isLoadingBottom = true;
            });
        }
    }

    ngOnInit() {
        this.dataRequest.section = this.type;
        this.dataRequest.isHeadline =  1;
        if (this.dataRequest.section !== 'main') {
            delete this.dataRequest.isHeadline;
        }
        this.route.parent.params.subscribe(params =>  this.dataRequest.sectionId = params.id);
        this.route.params.filter((params) => !_.find(this.newsList, {slug: params.view}))
            .subscribe((params: any) => {
                this.activePost = params.view;
                this.newsList = [];
                this.bufferNewsList = [];
                this.dataRequest.newsIds = null;
                this.dataRequest.tagName = params.tagName;
                this.dataRequest.tagType = params.tagType;
                this.dataRequest.firstLoad = true;
                this.isPreviousNews = false;
                this.isLoadingBottom = false;
                this.loadNews('next', params.view, true);
            });
        if (this.rootService.isBrowser()) {
            window.scroll(0, 0);
        }
    }
}
