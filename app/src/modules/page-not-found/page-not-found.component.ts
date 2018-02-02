import { AfterContentInit, Component, Inject, OnInit } from '@angular/core';
import { PLATFORM_ID } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { SeoService } from '../../+shared/services/seo.service';

@Component({
    templateUrl: './page-not-found.component.html',
    styleUrls: ['./page-not-found.component.scss']
})
export class PageNotFoundComponent implements AfterContentInit, OnInit {

    constructor(public seoService: SeoService, @Inject(PLATFORM_ID) private platformId: object) {
    }

    ngOnInit(): void {
        this.seoService
            .setTitle('Not Found')
            .setDescription('Error');
    }

    ngAfterContentInit(): void {
        if (!isPlatformBrowser(this.platformId)) {
            throw('Not Found');
        }
    }

}
