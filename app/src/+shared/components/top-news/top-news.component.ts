import { Component, Input, OnInit } from '@angular/core';
import { RootService } from '../../../modules/root/root.service';
import { TopNewsService } from '../../services/top-news.service';

@Component({
    selector: 'top-news',
    templateUrl: './top-news.component.html',
    styleUrls: ['./top-news.component.scss']
})
export class TopNewsComponent implements OnInit {
    currentIndex: number = 0;
    prevIndex: number;
    nextIndex: number;
    ready: boolean = false;
    @Input() sport;

    constructor(public rootService: RootService, public topNewsService: TopNewsService) {
    }

    ngOnInit() {
        this.topNewsService.loadAllTopNews(this.sport);
        this.topNewsService.ready$
            .filter(isReady => isReady)
            .subscribe(() => {
                this.loadIndexes(0);
                this.ready = true;
            });
    }

    loadIndexes(index) {
        this.currentIndex = index;
        this.nextIndex = this.topNewsService.nextIndex(index);
        this.prevIndex = this.topNewsService.prevIndex(index);
    }

    showSlide(index) {
        this.loadIndexes(index);
    }

}
