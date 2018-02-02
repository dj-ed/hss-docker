import {Directive, Input, EventEmitter, ElementRef, Output, HostListener} from '@angular/core';
import {NewsService} from "../services/news.service";
import {UserService} from "../services/user.service";

@Directive({
    selector: '[likeBtn]',
    outputs: ['updateCount']
})
export class LikeActionDirective {
    @Input() id;
    @Input() typeModel;
    @Output() updateCount = new EventEmitter();
    constructor(el: ElementRef, public newsService: NewsService, public userService: UserService) {

    }
    @HostListener('click') like() {
        if (this.userService.isLoggedIn()) {
            this.userService.setLike(this.typeModel, this.id).subscribe((likes) =>  {
               this.updateCount.emit(likes);
            });
        }
    }
}