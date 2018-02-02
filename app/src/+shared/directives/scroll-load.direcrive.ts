import {Directive, Input, EventEmitter, OnInit, OnDestroy, ElementRef} from '@angular/core';
import {Observable} from "rxjs/Observable";
import * as _ from "lodash";
import {RootService} from "../../modules/root/root.service";
import {Subject} from "rxjs/Subject";

@Directive({
    selector: '[scrollLoad]',
    outputs: ['loadTop', 'loadBottom', 'scroll']
})
export class ScrollLoadDirecrive implements OnInit, OnDestroy {

    @Input() public isLoadingTop?: boolean = false;
    @Input() public isLoadingBottom?: boolean = false;
    public isWork: boolean = true;
    public loadingBottomTrigger: boolean = true;
    public loadingTopTriger: boolean = true;
    public scrollPostParam: any = {
        postPosition: [],
        current: {},
        bottom: {}
    };
    public dataParams: any = {
        contWrap: {top: 0, paddingTop: 0, clientHeight: 0},
        breakBottomPoints: 0,
        breakTopPoints: 0,
        gap: 1600
    };
    public loadTop: EventEmitter<any> = new EventEmitter();
    public loadBottom: EventEmitter<any> = new EventEmitter();
    public scroll: EventEmitter<any> = new EventEmitter();
    public direction: 'top' | 'bottom';
    public conversationTriger: boolean = false;
    public scroll$ = new Subject();

    constructor(public contWrap: ElementRef, public rootService: RootService) {
    }

    conversionScroll() {
        let topRec = this.contWrap.nativeElement.getBoundingClientRect().top;
        topRec = topRec > 0 ? topRec : 0;
        if (document.body.querySelector('.top-menu')) {
            this.dataParams.contWrap.top = pageYOffset + document.body.querySelector('.top-menu').clientHeight;
            this.dataParams.contWrap.bottom = this.dataParams.contWrap.top + document.documentElement.clientHeight;
            this.direction = this.dataParams.contWrap.top > (pageYOffset + topRec + this.dataParams.contWrap.paddingTop) ? 'top' : 'bottom';
        }

    }

    conversion(): boolean {
        this.conversationTriger = true;
        const current = [].map.call(this.contWrap.nativeElement.children, (news, index) => {
            const begin = news.offsetTop;
            const end = news.offsetTop + news.clientHeight;
            const height = news.clientHeight;
            const id = news.dataset.id;
            const slug = news.dataset.slug;
            const title = news.querySelector('.cont').offsetTop;
            return {begin, end, height, id, slug, index, title};
        });
        const isEquil = _.isEqual(current, this.scrollPostParam.postPosition);
        this.scrollPostParam.postPosition = !isEquil ? current : this.scrollPostParam.postPosition;
        this.dataParams.contWrap.clientHeight = this.contWrap.nativeElement.clientHeight;
        this.dataParams.contWrap.breakBottomPoint = (this.contWrap.nativeElement.offsetTop + this.contWrap.nativeElement.clientHeight) - this.dataParams.gap;
        this.dataParams.contWrap.breakTopPoint = this.contWrap.nativeElement.offsetTop + this.dataParams.contWrap.paddingTop + 10;
        this.conversationTriger = false;
        return !isEquil;
    }

    emitScrollData() {
        this.scrollPostParam.postPosition.forEach((postInfo, index) => {
            if (postInfo.begin <= this.dataParams.contWrap['top'] && postInfo.end >= this.dataParams.contWrap['top']) {
                if (this.scrollPostParam['current'].id !== postInfo.id) {
                    this.scrollPostParam['current'] = postInfo;
                }
                /*
                if (this.scrollPostParam.postPosition[index + 1]) {
                    this.scrollPostParam['bottom'] = this.scrollPostParam.postPosition[index + 1].begin <= this.dataParams.contWrap['bottom'] ?
                        this.scrollPostParam.postPosition[index + 1].id : postInfo.id;
                } else {
                    this.scrollPostParam['bottom'] = null;
                }
                */

                this.scrollPostParam['current'].percent = (this.dataParams.contWrap['top'] - postInfo.begin) / (postInfo.height / 100);
                this.scrollPostParam['current'].direction = this.direction;
                this.scrollPostParam['current'].preTitle = postInfo.title >= this.dataParams.contWrap.top;
            }
        });
        if (Object.keys(this.scrollPostParam.current).length > 0) {
            this.scroll.emit(this.scrollPostParam);
            this.scroll$.next(this.scrollPostParam);
        }
    }


    refreshView() {
        this.scrollPostParam.current = {};
        this.scrollPostParam.postPosition = [];
        this.conversationTriger = true;
        setTimeout(() => {
            if (!this.rootService.isBrowser()) {
                return;
            }
            this.conversionScroll();
            this.emitScrollData();
            this.conversationTriger = false;
        }, 500);
    }

    ngOnInit() {
        if (!this.rootService.isBrowser()) {
            return;
        }

        const scroll = Observable.fromEvent(window, 'scroll').takeWhile(() => this.isWork).sampleTime(400);


        scroll.filter(() => this.dataParams.contWrap.clientHeight !== this.contWrap.nativeElement.clientHeight)
            .subscribe(() => this.conversion());


        scroll.subscribe(() => {
            if (this.loadingBottomTrigger != false || this.loadingTopTriger != false) {
                setTimeout(() => {

                    this.loadingBottomTrigger = false;               // loaded!
                    this.loadingTopTriger = false;                  // loaded!
                }, 300);
            }
            
            this.conversionScroll();
            this.emitScrollData();
        });


        const loadBottom = scroll
            .filter(() => this.dataParams.contWrap.breakBottomPoint <= this.dataParams.contWrap.top)
            .filter(() => this.isLoadingBottom && !this.loadingBottomTrigger && this.direction === 'bottom')
            .subscribe(() => {
                this.loadingBottomTrigger = true;
                this.loadBottom.emit();
            });

        this.dataParams.contWrap.paddingTop = +window.getComputedStyle(this.contWrap.nativeElement, null).getPropertyValue('padding-top').replace('px', '');

        Observable.fromEvent(window, 'resize').takeWhile(() => this.isWork).subscribe(() => {
            this.conversion();
            this.conversionScroll();
            this.emitScrollData();
        });

    }

    ngOnDestroy() {
        if (this.rootService.isBrowser()) {
            this.isWork = false;
        }
    }

}
