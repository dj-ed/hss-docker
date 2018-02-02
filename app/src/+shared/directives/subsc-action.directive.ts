import {
    Directive, Input, HostListener, Output, EventEmitter, OnInit, Renderer2, ElementRef,
    OnDestroy
} from '@angular/core';
import {UserService} from "../services/user.service";

@Directive({
    selector: '[subsBtn]',
    outputs: ['pushAllFavorites'],
    host: {'[class.in-fav]': 'isSubscribe'}
})
export class SubscActionDirective implements OnInit, OnDestroy {
    @Input('subsBtn') config: {
        modelId,
        modelType,
        type,
        role
    };

    @Output() pushAllFavorites: EventEmitter<any> = new EventEmitter();
    public isActiveSelect = false;
    public work = true;

    constructor(public userService: UserService, public render: Renderer2, public el: ElementRef) {
    }

    @HostListener('click')
    addFavorite() {
        if (this.userService.isLoggedIn()) {
            if (this.config.role === 'subs') {

                switch (this.config.type) {
                    case 'favorite':
                        this.userService.addToFavorite(this.config.modelType, this.config.modelId);
                        break;
                    case 'scrapbook':
                        this.userService.addToScrapbook(this.config.modelType, this.config.modelId);
                        break;
                    case 'event':
                        this.userService.addToEvents(this.config.modelType, this.config.modelId);
                }

            } else if (this.config.role === 'action') {

                this.isActiveSelect = !this.isActiveSelect;
                if (this.isActiveSelect) {
                    this.pushAllFavorites.emit(this.userService[this.config.type == 'favorite' ? 'favoritesList' : 'scrapbookList'][this.config.modelType]);

                } else {
                    this.pushAllFavorites.emit([]);

                }
            }
        }
    }

    get isSubscribe() {
        if (this.config.role === 'subs') {

            switch (this.config.type) {
                case 'favorite':
                    return this.userService.isFavorite(this.config.modelType, this.config.modelId);
                case 'scrapbook':
                    return this.userService.isInScrapbook(this.config.modelType, this.config.modelId);
                case 'event':
                    return this.userService.isAddedEvent(this.config.modelType, this.config.modelId);
            }

        } else {
            return this.isActiveSelect;
        }
    }

    ngOnInit() {
        this.render.setStyle(this.el.nativeElement, 'cursor', 'pointer');
        if (!this.config.modelId && this.config.role !== 'action') {
            this.render.setStyle(this.el.nativeElement, 'opacity', '0');
            while (this.el.nativeElement.children[0]) {
                this.el.nativeElement.removeChild(this.el.nativeElement.children[0]);
            }
        }

        this.userService.updateScrapbook$.takeWhile(() => this.work && this.config.role == 'action' && this.config.type === 'scrapbook')
            .subscribe((scrapbookList) => {
                if (this.isActiveSelect) {
                    this.pushAllFavorites.emit(scrapbookList[this.config.modelType]);
                }
            });
        this.userService.updateFavorites$.takeWhile(() => this.work && this.config.role == 'action' && this.config.type === 'favorite')
            .subscribe((favoritesList) => {
                if (this.isActiveSelect) {
                    this.pushAllFavorites.emit(favoritesList[this.config.modelType]);
                }
            });
    }


    ngOnDestroy() {
        this.work = false;
    }

}