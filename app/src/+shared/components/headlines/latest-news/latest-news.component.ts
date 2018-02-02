import { Component, OnInit, Input } from '@angular/core';
import { NewsShort } from '../../../../models/news.short.model';
import { RootService } from '../../../../modules/root/root.service';
import { ActivatedRoute } from "@angular/router";
import { NewsService } from "../../../services/news.service";
import { UserService } from "../../../services/user.service";

@Component({
    selector: 'latest-news',
    templateUrl: './latest-news.component.html',
    styleUrls: ['./latest-news.component.scss'],
})
export class LatestNewsComponent implements OnInit {
    @Input() type: string;
    @Input() title: string = 'Latest News';
    latestNews: NewsShort[] = [];
    parentId: any;
    public rootReady$;

    constructor(public rootService: RootService, public route: ActivatedRoute, public newsService: NewsService,
                public userService: UserService) {
        this.rootReady$ = this.rootService.ready$.filter(ready => ready);
    }

    ngOnInit() {
        this.route.parent.params.subscribe((params) => {
            this.parentId = params.id;
        });
        this.newsService.loadLatestNews({type: this.type}).subscribe((newsList) => {
            this.latestNews = newsList.map(news => new NewsShort(news));
        });

    }
}
