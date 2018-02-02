import {Component, OnInit } from '@angular/core';
import { RootService } from '../../root/root.service';

@Component({
    selector: 'all-schools',
    templateUrl: './all-schools.component.html',
    styleUrls: ['./all-schools.component.scss'],

})
export class AllSchoolsComponent implements OnInit{

    ngOnInit(){

    }

    constructor(public rootService: RootService) {
    }

}
