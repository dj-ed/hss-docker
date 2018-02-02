import { Component, Input, OnDestroy, OnInit } from '@angular/core';
import { NewsVideo } from '../../../../models/news.video.model';
import { DomSanitizer } from '@angular/platform-browser';
import { ActivatedRoute } from "@angular/router";
import { NewsService } from "../../../services/news.service";
import { UserService } from "../../../services/user.service";
import { RootService } from "../../../../modules/root/root.service";

@Component({
    selector: 'last-video-news',
    templateUrl: './last-video-news.component.html',
    styleUrls: ['./last-video-news.component.scss'],
})
export class LastVideoNewsComponent implements OnInit, OnDestroy {
    @Input() type: string;
    @Input() sectionId: number;
    @Input() title?: string = 'Latest Video News';
    lastVideoNews: NewsVideo;
    parentId;
    subscriber;

    constructor(public route: ActivatedRoute, public newsService: NewsService,
                public userService: UserService, public rootService: RootService) {
    }

    ngOnInit(): void {
        this.route.parent.params.subscribe((params) => {
            this.parentId = params.id;
        });

        this.subscriber = this.newsService.loadLastVideoNews({
            type: this.type,
        }).subscribe(news => {
            if (news) {
                this.lastVideoNews = new NewsVideo(news);
                if (this.lastVideoNews.videoType === 'iframe') {
                    this.lastVideoNews['embedHtml'] = this.rootService.getSecureEmbed(this.lastVideoNews.videoUrl);
                }
            }
        });
    }

    ngOnDestroy() {
        // this.subscriber.unsubscribe();
    }

}
