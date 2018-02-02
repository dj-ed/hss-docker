import {
    OnInit, Component, Input, ViewChild, ElementRef, OnDestroy, ViewChildren, Output,
    EventEmitter
} from "@angular/core";
import {ActivatedRoute} from "@angular/router";
import {RootService} from "../../../../modules/root/root.service";
import {DomSanitizer} from "@angular/platform-browser";
import {SwiperDirective} from "ngx-swiper-wrapper";
import {Observable} from "rxjs/Observable";
import {GalleryService} from "../gallery.service";
import {ModalDirective} from "../../../directives/modal.directive";
import {Media} from '../../../../models/media.model';

@Component({
    selector: 'media-gallery',
    templateUrl: './media-gallery.component.html',
    styleUrls: ['./media-gallery.component.scss']
})
export class MediaGaleryComponent implements OnInit, OnDestroy {
    @Input() config?: {
        id?: number,
        isSectionAlbum?: boolean,
        albumsList?: any,
        mediaList?: any,
        currentMedia?: any,
        modalDirective?: ModalDirective,
        openedAlbum?: any,
        isOpenComments?: any,
        liveLoad: 'album' | 'modal' | 'all',
        type?: string,
        viewType: 'box' | 'modal',
        isLoadingMoreSlides?: boolean
    } = {isSectionAlbum: true, id: null, liveLoad: null, type: null, viewType: 'box'};
    @ViewChild('contain') contain: ElementRef;
    @ViewChild('swiperContain') swiperContain: ElementRef;
    @ViewChildren(SwiperDirective) public swiperWrappers: SwiperDirective | any;
    @ViewChild('comments') comments;
    @Output('mediaLoad') mediaLoad: EventEmitter<any> = new EventEmitter();
    @Output('albumLoad') albumLoad: EventEmitter<any> = new EventEmitter();
    openedAlbum;
    gallery;
    galleryId;
    currMedia;
    isShowDescription;
    isFullScreen;
    isAutoplaySwipper;
    isWork = true;
    playingVideo;
    intervalAutoplaySlider$;
    isOpenAlbumList = false;
    initLiveLoad = false;

    constructor(public galleryService: GalleryService, public route: ActivatedRoute, public rootService: RootService, public sanitizer: DomSanitizer) {
    }

    changeCurrMedia(media) {
       // debugger
        this.currMedia = media;
        if (this.config.isOpenComments) {
            this.comments.getComments({type: 'gallery', id: this.currMedia.id}, true);
        }
    }

    getIndexForOpenedAlbum() {
        return this.config.albumsList.findIndex((album) => album.id === this.galleryId);
    }

    getIndexForCurrentMedia() {
        return this.gallery.findIndex((media) => media.id === this.currMedia.id);
    }

    playVideo(mediaIndex) {
        const iframe = this.contain.nativeElement.querySelector('.main-media').querySelector('div.iframe > iframe');
        iframe.src = iframe.src + '&autoplay=1';
        this.playingVideo = true;
    }

    toggleAutoplaySlider(stop?: boolean) {
       // debugger
        if (stop) {
            this.isAutoplaySwipper = false;
            return;
        }

        this.isAutoplaySwipper = !this.isAutoplaySwipper;
        if (!this.intervalAutoplaySlider$) {
            this.intervalAutoplaySlider$ = Observable.interval(2000).takeWhile(() => this.isWork)
                .filter(() => this.isAutoplaySwipper)
                .map(() => {
                    return {
                        currSlide: this.swiperWrappers.last.getIndex(),
                        currMedia: this.gallery.findIndex((media) => media.id === this.currMedia.id)
                    };
                });

            this.intervalAutoplaySlider$
                .subscribe(({currSlide, currMedia}) => {
                    if (currSlide !== currMedia) {
                        this.swiperWrappers.last.setIndex(currMedia);
                    } else {
                        this.swiperWrappers.last.nextSlide();
                    }
                });

            this.intervalAutoplaySlider$
                .filter(({currSlide, currMedia}) => {
                    this.isAutoplaySwipper = this.gallery[++currMedia] !== undefined;
                    return this.isAutoplaySwipper;
                }).subscribe(({currSlide, currMedia}) => {
                this.detectSliderLoad('media');
                this.playingVideo = false;
                this.currMedia = this.gallery[++currMedia];
                if (this.config.isOpenComments) {
                    this.comments.getComments({type: 'gallery', id: this.currMedia.id}, true);
                }
            });
        }
    }

    toggleFullScreen(close?: true) {
        this.isFullScreen = close ? false : !this.isFullScreen;
        if (this.config.modalDirective) {
            this.config.modalDirective.openedFullScreenInside = this.isFullScreen;
        } else {
            document.body.style.overflow = this.isFullScreen ? 'hidden' : '';
        }
    }

    toggleDescriptionPanel(close?: boolean) {
        this.isShowDescription = close ? false : !this.isShowDescription;
    }

    getVideosEmbed(gallery) {
        gallery = gallery.map((media) => {
            if (media.mediaType === 'video' && media.isIframe) {
                media.youTubeVideoId = this.rootService.getYouTubeId(media.mediaUrl);
                media.embedHtml = this.rootService.getSecureEmbed(media.mediaUrl, {fs: 0});
            }
            return media;
        });
        return gallery;
    }

    loadGallery(id) {
        //debugger
        this.toggleAutoplaySlider(true);
        this.toggleDescriptionPanel(true);
        this.galleryService.loadOneGallery(this.config.type, id)
            .subscribe((result) => {
                this.galleryId = id;
                this.initLoad(result, result[0]);
            });
    }

    detectSliderLoad(type: 'album' | 'media') {
        // debugger
        // is allow liveLoad?
        if (this.config.liveLoad !== type && !this.config.liveLoad) {
            return;
        }

        // is allow load now?
        if (!this.config.isLoadingMoreSlides && this.initLiveLoad) {
            const order = type == 'album' ? 'first' : 'last';
            const swiperRef = this.swiperWrappers[order].elementRef.nativeElement;
            const viewIndex = this.swiperWrappers[order].getIndex();
            const countInContain = Math.floor(swiperRef.clientWidth / swiperRef.querySelector('.swiper-slide').clientWidth);
           // debugger
            const length = type === 'album' ? this.config.albumsList.length : this.gallery.length;
            if (length <= countInContain) {
                return;
            }
            if (2 >= length - (viewIndex + countInContain)) {
                this[type + 'Load'].emit();
                this.config.isLoadingMoreSlides = true;
                console.log('load more slides');
            }
        }
    }

    toggleAlbumsList(e) {
        if (!this.isOpenAlbumList) {
            this.swiperWrappers.first.setIndex(this.getIndexForOpenedAlbum(), 0);
        }
        setTimeout(() => {
            this.isOpenAlbumList = !this.isOpenAlbumList;
        }, 100);

    }

    initLoad(gallery, currMedia) {
        this.gallery = this.getVideosEmbed(gallery);
        this.currMedia = currMedia || this.gallery[0];
        this.galleryId =  this.galleryId ? this.galleryId : this.config.id;
        // open select album in album list
        if (this.config.albumsList) {
            const indexOpenedAlbum =  this.getIndexForOpenedAlbum();
            this.openedAlbum = this.config.albumsList[indexOpenedAlbum];
        }

        // open select media in media list
        if (this.config.mediaList) {
            setTimeout(() => {
                this.swiperWrappers.last.setIndex(this.getIndexForCurrentMedia(), 20);
            }, 300);
        }

        // load comments if it's open
        if (this.config.isOpenComments) {
            this.comments.getComments({type: 'gallery', id: this.currMedia.id}, true);
        }

    }

    ngOnInit() {
        if (this.config.mediaList) {
            this.initLoad(this.config.mediaList, this.config.currentMedia);
        } else {
            this.loadGallery(this.config.id);
        }
        Observable.fromEvent(document, 'keyup').takeWhile(() => this.isWork)
            .filter((event: KeyboardEvent) => event.keyCode === 27 && this.isFullScreen)
            .subscribe(() => {
                this.toggleFullScreen();
            });
    }

    ngOnDestroy() {
        this.isWork = false;
        if (this.rootService.isBrowser()) {
            document.body.style.overflow = '';
        }
    }
}