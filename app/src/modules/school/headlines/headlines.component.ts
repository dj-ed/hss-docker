import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { SchoolService } from '../school.service';
import { School } from '../../../models/school.model';
import {RootService} from "../../root/root.service";

@Component({
    templateUrl: './headlines.component.html',
    styleUrls: ['./headlines.component.scss']
})
export class HeadlinesComponent implements OnInit {
    school: School;

    constructor(public seoService: SeoService, public schoolService: SchoolService, public  rootService: RootService) {
    }

    ngOnInit(): void {
       this.schoolService.subscribeSchool().subscribe(school => {
           this.school = school;
           this.seoService
              .setTitle('School Headlines')
              .setDescription('School Page');
        });
    }

}
