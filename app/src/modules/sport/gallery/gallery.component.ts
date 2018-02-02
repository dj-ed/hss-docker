import { Component, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';

@Component({
    templateUrl: './gallery.component.html'
})
export class GalleryComponent implements OnInit {

    constructor(public seoService: SeoService) {
    }

    ngOnInit(): void {
        this.seoService
            .setTitle('Sport')
            .setDescription('Sport Gallery');
    }
}
