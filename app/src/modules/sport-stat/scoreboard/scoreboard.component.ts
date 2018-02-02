import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { RootService } from "../../root/root.service";
import { ActivatedRoute } from "@angular/router";

@Component({
    templateUrl: './scoreboard.component.html',
})
export class ScoreboardComponent implements OnInit {

    constructor(public seoService: SeoService, public rootService: RootService, router: ActivatedRoute) {
    }

    ngOnInit(): void {
        this.seoService
            .setTitle('Sport')
            .setDescription('Sport Page');
    }
}
