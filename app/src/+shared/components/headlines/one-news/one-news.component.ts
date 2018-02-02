import {
    OnInit, Component, Input, EventEmitter, ViewChild, ElementRef, Output, OnDestroy
} from "@angular/core";
import {DomSanitizer} from '@angular/platform-browser';
import {ActivatedRoute} from "@angular/router";
import {RootService} from "../../../../modules/root/root.service";
import {SwiperDirective} from "ngx-swiper-wrapper";
import {News} from "../../../../models/news.model";
import {UserService} from "../../../services/user.service";
import {NewsService} from "../../../services/news.service";
import {Observable} from "rxjs/Observable";

@Component({
    selector: 'one-news',
    templateUrl: './one-news.component.html',
    styleUrls: ['./one-news.component.scss'],
})
export class OneNewsComponent implements OnInit, OnDestroy {
    @ViewChild('wrap') public wrap: ElementRef;
    @ViewChild(SwiperDirective) public swiperWrapper: SwiperDirective;
    @Input() public news?: News = null;
    @Input() public newsId;
    @Input() public section;
    @Input() public parentId;
    @Input() public open?: boolean = false;
    @Input() i;

    @Input()
    set activeSlider(value) {
        this._sctiveSlider = value;
        if (value) {
            setTimeout(() => {
                this.swiperWrapper.update();
            }, 200);
        }
    }

    @Input() public sliderType: string = '';
    @Input() scrollObservable: Observable<any>;
    @Output() public openPost: EventEmitter<any> = new EventEmitter();
    @Output() public openComment: EventEmitter<any> = new EventEmitter();
    @Output() public activateSlider: EventEmitter<any> = new EventEmitter();
    public _sctiveSlider;
    public activeComments: boolean;
    public slider: any = {init: false};
    public isWork = false;
    public observerOnDOM: MutationObserver;

    constructor(public sanitizer: DomSanitizer, public route: ActivatedRoute,
                public rootService: RootService, public userService: UserService, public newsService: NewsService) {
        if (this.news) {
            this.news = new News(this.createNews(this.news));
        }
    }

    toggleComment(id: any, act: 'open' | 'close') {
        this.activeComments = act === 'open';
        if (this.activeComments) {
            this.openComment.emit(this.news.id);
        } else {
            this.openComment.emit(null);
        }
    }

    togglePost(act: 'open' | 'close') {
        this.open = act === 'open';
        if (this.open) {
            this.openPost.emit(this.news.slug);
        } else {
            this.openPost.emit(null);
        }
    }


    createNews(news) {
        if (typeof news.text === 'object') {
            news.textShort = this.sanitizer.bypassSecurityTrustHtml(news.text.changingThisBreaksApplicationSecurity);
        } else {
            news.textShort = this.sanitizer.bypassSecurityTrustHtml(news.text);
        }

        news.media.data = news.media.data.map((media) => {
            if (media.type === 'iframe') {
                media['embedHtml'] = this.rootService.getSecureEmbed(media.fileUrl);
            }
            return media;
        });
        return news;
    }

    playVideo(mediaIndex) {
        const iframe = this.wrap.nativeElement.querySelectorAll('.top-slider-item')[+mediaIndex].querySelector('iframe');
        iframe.src = iframe.src + '&autoplay=1';
    }

    setDataSlider(transition?) {
        let transform;
        this.slider.carousel = this.wrap.nativeElement.querySelector('.other-media-wrap-carousel');
        if (!this.slider.carousel) {
            this.slider.isViewSlider = false;
            return;
        }
        if (typeof transition === 'undefined') {
            transform = this.slider.carousel.style.transform || this.slider.carousel.style.webkitTransform || this.slider.carousel.style.mozTransform ||
                'translate3d(0px, 0, 0)';
            this.slider.translate = transform.replace('translate3d(', '').split(',')[0].match(/\d/g).join('');
            this.slider.translate = 0 - this.slider.translate;
        }
        this.slider.widthSlide = this.wrap.nativeElement.querySelector('.one-media').clientWidth;
        this.slider.currentIndex = Math.round(Math.abs(this.slider.translate) / this.slider.widthSlide);
        this.slider.allMedia = this.news.media.data.length;
        this.slider.widthContain = this.wrap.nativeElement.querySelector('.slider-contain-wrap').clientWidth;
        this.slider.countInContain = Math.floor(this.slider.widthContain / this.slider.widthSlide);
        this.slider.isViewSlider = this.slider.allMedia > this.slider.countInContain;
        this.slider.isViewPrev = this.slider.translate < 0 && this.slider.isViewSlider;
        this.slider.isViewNext = this.slider.maxTransition !== this.slider.translate && this.slider.isViewSlider;
        this.slider.countInContain = this.slider.isViewPrev ? this.slider.countInContain - 1 : this.slider.countInContain;
        this.slider.countInContain = this.slider.isViewNext ? this.slider.countInContain - 1 : this.slider.countInContain;
        this.slider.maxTransition = 0 - (this.slider.allMedia - this.slider.countInContain) * this.slider.widthSlide;
        this.slider.init = true;
    }


    changeSlides(directive: 1 | -1) {
        this.setDataSlider();

        if (directive === -1) { // <--
            this.slider.translate += this.slider.countInContain * this.slider.widthSlide;
            this.slider.translate = this.slider.translate > 0 ? 0 : this.slider.translate;
        }
        if (directive === 1) { // -->
            this.slider.translate -= this.slider.countInContain * this.slider.widthSlide;
            this.slider.translate = this.slider.translate < this.slider.maxTransition ? this.slider.maxTransition : this.slider.translate;
        }
        this.setDataSlider(this.slider.translate);
        this.slider.carousel.style.transition = 'all 300ms';
        this.slider.carousel.style.transform = 'translate3d(' + this.slider.translate + 'px, 0, 0)';
    }

    initAfterRenderSlider(cb) {
        this.observerOnDOM = new MutationObserver(mutations => {
            if (this.wrap.nativeElement.querySelectorAll('.swiper-slide').length === this.news.media.data.length && this.rootService.isBrowser()) {
                cb();
            }
        });
        this.observerOnDOM.observe(this.wrap.nativeElement, {childList: true});
    }

    ngOnInit() {
        this.isWork = true;

        if (this.news) {
            this.news = new News(this.createNews(this.news));
        }
        if (!this.rootService.isBrowser()) {
            return;
        }
        if (!this.news && this.newsId) {
            this.newsService
                .loadNews({newsId: this.newsId})
                .do((newsData) => this.news = new News(this.createNews(newsData)))
                .filter(() => this.sliderType === 'contain')
                .subscribe(() => {
                    this.initAfterRenderSlider(this.setDataSlider.bind(this));
                });
        } else if (this.news && this.sliderType === 'contain' && this.rootService.isBrowser()) {
            this.initAfterRenderSlider(this.setDataSlider.bind(this));
        } else if (this.sliderType === 'swiper' && this.rootService.isBrowser()) {
            //  this.initAfterRenderSlider(() => this.slider.lengthOnRight = this.news.media.data.length - (this.swiperWrapper.getIndex() + 1));
        }
    }

    ngOnDestroy() {
        this.isWork = false;
        if (this.rootService.isBrowser() && this.news && this.observerOnDOM) {
            this.observerOnDOM.disconnect();
        }
    }
}