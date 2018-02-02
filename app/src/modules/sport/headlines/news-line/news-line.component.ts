import {
    Component, Input, ViewChild, OnDestroy, ElementRef, EventEmitter,
    Output, AfterViewInit
} from '@angular/core';
import {Observable} from "rxjs/Observable";
import {RootService} from "../../../root/root.service";
import {SwiperDirective} from "ngx-swiper-wrapper";
import {News} from "../../../../models/news.model";

@Component({
    selector: 'news-line',
    templateUrl: './news-line.component.html',
    styleUrls: ['../../../../styles/headlines.scss', './news-line.component.scss'],
})
export class NewsLineComponent implements OnDestroy, AfterViewInit {

    @Input() newsList: News[] = [];
    @Input() scrollObservable: Observable<any>;
    @ViewChild('sliderWrap') public sliderWrap: ElementRef;
    @ViewChild(SwiperDirective) public swiperWrapper: SwiperDirective;
    @Output() public fixedPost: EventEmitter<any> = new EventEmitter();
    @Output() public readPost: EventEmitter<any> = new EventEmitter();
    public sliderParams: any = {};
    public isWork: boolean = true;
    public slider;
    public isReadyRoot: boolean = false;
    public percent;
    public observerOnDOM;

    constructor(public rootService: RootService) {
    }

    setScroll() {

        if (!this.sliderParams.init) {
            this.sliderParams.slideWidth = this.sliderWrap.nativeElement.querySelector('.one_more_news').clientWidth;
            this.sliderParams.clientWidth = document.documentElement.clientWidth;
            this.sliderParams.viewCount = Math.floor(this.sliderParams.clientWidth / this.sliderParams.slideWidth);
            this.sliderParams.init = true;
        }
        this.sliderParams.breakPost = this.swiperWrapper.getIndex() + this.sliderParams.viewCount;
        if (this.sliderParams.breakPost <= this.slider.current.index) { // ->
            this.sliderParams.breakPost = this.slider.current.index + 1;
            this.swiperWrapper.setIndex(this.sliderParams.breakPost);
        }
        if (this.slider.current.index <= (this.sliderParams.breakPost - this.sliderParams.viewCount)) { // <-
            this.sliderParams.breakPost = this.slider.current.index + this.sliderParams.viewCount;
            this.swiperWrapper.setIndex(this.sliderParams.breakPost - this.sliderParams.viewCount);
        }

    }

    ngAfterViewInit() {
        if (!this.rootService.isBrowser()) {
            return;
        }

        this.scrollObservable
            .filter((scroll) => scroll.current).subscribe((scroll) => {
            this.slider = scroll;
            this.setScroll();
        });


        Observable.fromEvent(window, 'resize').takeWhile(() => this.isWork)
            .switchMap(() => this.rootService.ready$.filter(ready => ready))
            .throttle(val => Observable.interval(150))
            .filter(() => this.isReadyRoot && this.slider)
            .subscribe(() => {
                this.sliderParams.init = false;
                this.setScroll();
            });

        this.rootService.ready$.filter(ready => ready).subscribe((ready) => this.isReadyRoot = true);
        this.newsList = this.newsList.map(news => new News(news));

        if (this.rootService.isBrowser()) {
            /*
            this.observerOnDOM = new MutationObserver(mutations => {
                if (this.swiperWrapper) {
                    this.swiperWrapper.setIndex(this.slider.current.index);
                }

            });
            this.observerOnDOM.observe(this.sliderWrap.nativeElement, {childList: true});
            */
        }

    }

    ngOnDestroy() {
        if (this.rootService.isBrowser()) {
            this.isWork = false;
            if (this.observerOnDOM) {
                this.observerOnDOM.disconnect();
            }
        }
    }
}