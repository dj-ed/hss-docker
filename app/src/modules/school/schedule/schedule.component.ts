import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { SchoolService } from '../school.service';
import { School } from '../../../models/school.model';
import { ActivatedRoute, Router } from "@angular/router";
import { RootService } from "../../root/root.service";
import { AjaxService } from "../../../+shared/services/ajax.service";

@Component({
    templateUrl: './schedule.component.html',
    styleUrls: ['./../../../+shared/modules/schedule/schedule.scss', './../../../styles/schedule.scss'],
})
export class ScheduleComponent implements OnInit {
    school: School;
    sportId = null;
    routerId;
    rootReady$;

    constructor(public seoService: SeoService, public schoolService: SchoolService, public route: ActivatedRoute, public rootService: RootService, public router: Router,
                public ajaxService: AjaxService) {
        this.rootReady$ = this.rootService.ready$.filter((ready) => ready);
        this.rootReady$.subscribe(() => {
            this.route.params
                .subscribe((params: any) => {
                    if (params.sport) {
                        const sport = this.rootService.sportByName(params.sport);
                        if (sport) {
                            this.rootService.changeSport(sport.id);
                        } else {
                            this.router.navigate(['/not-found']);
                        }

                    } else {
                        this.rootService.changeSport(undefined);
                    }
                });
        });
    }

    ngOnInit(): void {
        this.schoolService.subscribeSchool().subscribe(school => {
            this.school = school;

            this.seoService
                .setTitle('School ' + school.name)
                .setDescription('School Page');
        });
    }
}
