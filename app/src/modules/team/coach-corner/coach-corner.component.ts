import {Component, OnDestroy, OnInit} from '@angular/core';
import {ActivatedRoute} from "@angular/router";
import {CoachShortArticle} from "../../../models/coach-short-article.model";
import {CoachCorner} from "../../../models/coach-corner.model";
import {TeamService} from "../team.service";

@Component({
    selector: 'coach-corner',
    templateUrl: './coach-corner.component.html',
})
export class CoachCornerComponent implements OnInit, OnDestroy {
    coachCorner;
    isWork = true;
    constructor(public route: ActivatedRoute, public  teamService: TeamService) {

    }

    ngOnInit() {
        this.route.params.subscribe((params) => {
            this.teamService.loadCoachCorner(params.id).subscribe((data) => {
                    if  (data) {
                        data.articles.data = data.articles.data.map(news => new CoachShortArticle(news));
                        this.coachCorner = new CoachCorner(data);
                    }
               });
        });
    }

    ngOnDestroy() {
        this.isWork = false;
    }
}