import {Directive, EventEmitter, Output, OnInit, OnDestroy, ElementRef, Input, HostListener} from '@angular/core';
import {Observable} from "rxjs/Observable";
import {RootService} from "../../modules/root/root.service";
import * as _ from 'lodash';

@Directive({
    selector: '[scrollLabel]'
})
export class ScrollStickerDirective implements OnInit, OnDestroy {
    @Input('config')
    set config(config) {
        if (!this.rootService.isBrowser()) {
            return;
        }
        this.disabled = true;
        this._config = config;
        this.getHeightLevel();
        this.disabled = false;
    };

    @Output() scrollDetect: EventEmitter<any> = new EventEmitter();
    _config: { levelsClasses: string[], searchElements: { name: string, viewLevel: number }[], order: string };
    pointsStore: Array<any> = [];
    observerOnDOM;
    work = true;
    levelsHeight = [];
    disabled;
    scrollNavTimeout;

    constructor(public el: ElementRef, public rootService: RootService) {

    }

    getOffsetTop(elem) {
        return elem.getBoundingClientRect().top >= 0 ? elem.getBoundingClientRect().top + pageYOffset : pageYOffset - Math.abs(elem.getBoundingClientRect().top);
    }


    calcAllPositions() {
        this.pointsStore = _.map(this.el.nativeElement.querySelectorAll('.team-layer[data-isOpen=true]'),
            (elem: HTMLElement) => {
                const begin = this.getOffsetTop(elem);
                return {
                    elem,
                    dataset: Object.assign({}, elem.dataset),
                    begin,
                    end: begin + elem.clientHeight,
                }
            });
    }

    checkPosition() {
        const res = {};
        _.forEach(this.pointsStore, (point) => {
            const s = pageYOffset + this.levelsHeight[+point.dataset.level];
            res[point.dataset.type] = s >= point.begin && s < point.end ? _.cloneDeep(point) : res[point.dataset.type];
        });
        this.scrollDetect.emit(res);
    }

    getHeightLevel() {
        this.levelsHeight = [];
        let height = document.body.querySelector('.top-menu').clientHeight;
        _.forEach(this._config.levelsClasses, (levelClasses, index) => {
            this.levelsHeight[index] = document.body.querySelector('.' + levelClasses).clientHeight + height;
            height += document.body.querySelector('.' + levelClasses).clientHeight;
        });
    }

    @HostListener('transitionend', ['$event']) onTransitionend(e: TransitionEvent) {
        if (e.propertyName === 'transform') {
            this.refreshView();
        }
    }

    scrollToChar(data, duration = 150, cb?) {
        if (duration <= 0) {
            if (cb) {
                cb();
            }
            return;
        }
        this.calcAllPositions();
        Object.keys(data).forEach(key => data[key] = String(data[key]));
        const searchElem = _.find(this.pointsStore, charElem => _.find([charElem.dataset], data));
        if (searchElem) {
            const difference = (searchElem.begin - this.levelsHeight[+data.level]) - pageYOffset;
            const perTick = difference / duration * 10;

            setTimeout(() => {
                window.scroll(0, pageYOffset + perTick);
                if ((pageYOffset - this.levelsHeight[+data.level]) === this.getOffsetTop(searchElem.elem)) {
                    if (cb) {
                        cb();
                    }
                    return;
                }
                this.scrollToChar(data,duration - 10,  cb);
            }, 0);
        } else {
            this.scrollNavTimeout = setTimeout(() => {
                this.scrollToChar(data, duration,  cb);
            }, 200);
        }
    }

    refreshView() {
        if (this.disabled) {
            clearInterval(this.disabled);
        }
        this.disabled = setTimeout(() => {
            this.disabled = null;
            this.calcAllPositions();
            this.checkPosition();
        }, 400);
    }

    ngOnInit() {
        if (!this.rootService.isBrowser()) {
            return;
        }

        this.observerOnDOM = new MutationObserver((mutations) => {
            this.calcAllPositions();
        });
        this.observerOnDOM.observe(this.el.nativeElement, {attributes: true, childList: true, characterData: true});

        Observable.fromEvent(window, 'wheel')
            .takeWhile(() => this.work)
            .filter(() => this._config && !this.disabled)
            .subscribe(() => {
                this.checkPosition();
            });
        Observable.fromEvent(window, 'resize')
            .takeWhile(() => this.work)
            .throttleTime(300)
            .subscribe(() => {
                this.disabled = true;
                this.getHeightLevel();
                this.calcAllPositions();
                this.disabled = false;
                this.checkPosition();
            });
    }

    ngOnDestroy() {
        if (this.rootService.isBrowser()) {
            this.observerOnDOM.disconnect();
            this.work = false;
        }
    }

}