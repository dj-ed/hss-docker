import { Component, OnInit, Renderer2, ViewEncapsulation } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { SchoolService } from './school.service';
import { RootService } from '../root/root.service';

@Component({
    templateUrl: './school.component.html',
    styleUrls: [
        '../../styles/team.scss',
        '../../styles/schedule.scss',
        '../../styles/school.scss',
        './school.component.scss'
    ],
    encapsulation: ViewEncapsulation.None

})
export class SchoolComponent implements OnInit {

    constructor(public route: ActivatedRoute, public schoolService: SchoolService, public rootService: RootService,
                private renderer: Renderer2) {
    }

    ngOnInit(): void {
        this.route.params.subscribe(params => {
            this.schoolService.loadSchoolCommon(+params['id']);
        });
    }

    onDeactivate() {
        this.renderer.setProperty(document.body, 'scrollTop', 0);
    }
}
