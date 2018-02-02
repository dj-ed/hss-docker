import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../+shared/services/seo.service';

@Component({
    templateUrl: './map.component.html',
})
export class IndexComponent implements OnInit {

    constructor(public seoService: SeoService) {
    }

    ngOnInit(): void {
        this.seoService
            .setTitle('Map')
            .setDescription('Map Page');
    }
}
