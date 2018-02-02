import {Component, OnInit} from '@angular/core';
import {SeoService} from '../../../+shared/services/seo.service';
import {RootService} from "../../root/root.service";
import {DomSanitizer} from "@angular/platform-browser";
import {Location} from "@angular/common";

@Component({
    templateUrl: './text-page.componet.html',
    styleUrls: ['./text-page.componet.scss'],
})
export class TextPageComponent implements OnInit {

    textData;
    constructor(public sanitizer: DomSanitizer, public seoService: SeoService, public rootService: RootService, public location: Location) {

    }

    ngOnInit() {
        this.seoService
            .setTitle('HSS Abous Us')
            .setDescription('HSS Abous Us Page');
        let url;
        switch (this.location.path()) {
            case '/about-us' : url = 'about_us'; break;
            case '/contact-us' : url = 'contact_us'; break;
            case '/terms-of-use' : url = 'terms_of_use'; break;
        }
        this.rootService.getTextInfo(url)
            .subscribe((res) => {
                const contentText = this.sanitizer.bypassSecurityTrustHtml(res.text);
                this.textData = res;
                this.textData.text = contentText;
            });
    }
}