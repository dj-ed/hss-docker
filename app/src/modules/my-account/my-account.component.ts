import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../+shared/services/seo.service';
import { AjaxService } from '../../+shared/services/ajax.service';

@Component({
    templateUrl: './my-account.component.html',
})
export class MyAccountComponent implements OnInit {

    constructor(public seoService: SeoService, public ajaxService: AjaxService) {
    }

    ngOnInit(): void {
        this.seoService
            .setTitle('My Account')
            .setDescription('My Account');
    }

    testAuthRequest() {
        this.ajaxService.post('my-account').subscribe(result => {
            console.log(result);
        });
    }
}
