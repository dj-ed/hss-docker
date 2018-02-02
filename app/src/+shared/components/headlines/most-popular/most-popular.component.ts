import {Component, Input, OnInit} from '@angular/core';
import {NewsShort} from "../../../../models/news.short.model";
import {RootService} from "../../../../modules/root/root.service";
import {UserService} from "../../../services/user.service";
import {NewsService} from "../../../services/news.service";

@Component({
    selector: 'most-popular',
    templateUrl: './most-popular.component.html',
    styleUrls: ['./most-popular.component.scss'],
})
export class MostPopularComponent implements OnInit{
    @Input() public type;
    public newsList: NewsShort[] = [];
    public rootReady$;
    constructor(public rootService: RootService, public userService: UserService, public newsService: NewsService) {
        this.rootReady$ = this.rootService.ready$.filter(ready => ready);
    }

    ngOnInit() {
        this.newsService.loadPopularNews(this.type).subscribe(newsList => {
            this.newsList = newsList.map(news => new NewsShort(news));
            if (this.rootService.currentSportId) {
                   this.newsList = this.newsList.slice(0, 2);
                }
        });
    }
}
