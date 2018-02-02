import { Component, OnInit } from '@angular/core';
import { apperaAnimation } from './location-zip.animations';
import { HeaderService } from "../root-header.service";
import {RootService} from "../../root.service";

@Component({
    selector: 'location-zip',
    templateUrl: './location-zip.component.html',
    styleUrls: ['./location-zip.component.scss'],
    animations: [apperaAnimation],
})

export class LocationZipComponent implements OnInit {
    zips = [];
    zip_input: string = '';
    dropOpen: boolean = false;

    constructor(public headerService: HeaderService, public rootService: RootService) {
    }

    ngOnInit(): void {
    }

    testreg(e){
        let re = /[0-9]/;
        let test = e.target.value.split('');
        let a = [];
        test.forEach((item)=>{
            re.test(item) && a.push(item);
        });
        this.zip_input = a.join('');
        this.zip_input.length > 2 && this.getZip(this.zip_input);
    }

    getZip(text){
        this.headerService.getLocationZip(text).subscribe(res => {
            this.zips = res.data;
        });
        this.dropOpen = true;
    }

}
