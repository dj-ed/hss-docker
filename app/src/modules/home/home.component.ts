import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { SeoService } from '../../+shared/services/seo.service';
import { UserService } from '../../+shared/services/user.service';
import { RootService } from '../root/root.service';
import { ActivatedRoute, Router } from "@angular/router";

@Component({
    templateUrl: './home.component.html',
    styleUrls: ['../../styles/home-owerview.scss'],
    encapsulation: ViewEncapsulation.None
})
export class HomeComponent implements OnInit {

    data: number[];

    constructor(public seoService: SeoService, public userService: UserService, public rootService: RootService, public route: ActivatedRoute, public router: Router) {
        this.route.params.subscribe(params => {
            // if latest season redirect home
            // if (params['season']) {
            //     const list = this.rootService.seasonList;
            //     if (list.length && list[0]['titleShort'] === params['season']) {
            //         this.router.navigate(['/']);
            //     }
            // }
            //
        });
    }

    ngOnInit(): void {
        this.seoService
            .setTitle('HSS Overview')
            .setDescription('HSS Overview Page');


    }
}
