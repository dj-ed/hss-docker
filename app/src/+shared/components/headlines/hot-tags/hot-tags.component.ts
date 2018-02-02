import { Component, Input, OnInit } from '@angular/core';
import { RootService } from "../../../../modules/root/root.service";
import { NewsService } from "../../../services/news.service";
import { ActivatedRoute } from "@angular/router";

@Component({
    selector: 'hot-tags',
    templateUrl: './hot-tags.component.html',
    styleUrls: ['./hot-tags.component.scss']
})
export class HotTagsComponent implements OnInit {

    @Input() title: string;
    @Input() section: string;
    isReadyRoot;
    parentId;
    dynamicTags;

    constructor(public rootService: RootService, public route: ActivatedRoute, public newsService: NewsService) {
        this.rootService.ready$.filter(ready => ready).subscribe(() => this.isReadyRoot = true);
    }

    ngOnInit() {
        const dataRequest: any = {isHeadline: 1, section: this.section};
        if (dataRequest.section !== 'main') {
            delete dataRequest.isHeadline;
        }
        this.route.parent.params.subscribe(params => {
            dataRequest.sectionId = params.id;
            this.parentId = params.id;
            this.newsService.loadDynamicHotTags(dataRequest)
                .subscribe((tags) => this.dynamicTags = tags);
        });

    }
}
