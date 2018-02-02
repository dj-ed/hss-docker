import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { ActivatedRoute } from "@angular/router";
import { RootService } from "../../root/root.service";

@Component({
    templateUrl: './leaderboard.component.html',
})
export class LeaderboardComponent implements OnInit {

    constructor(public seoService: SeoService, public rootService: RootService, router: ActivatedRoute) {
    }

    ngOnInit(): void {
        this.seoService
            .setTitle('Sport')
            .setDescription('Sport Page');
    }
}
