import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';

@Component({
    templateUrl: './live.component.html',
    styleUrls: ['../../../styles/live-stream.scss'],
    encapsulation: ViewEncapsulation.None,
})
export class LiveComponent implements OnInit {

    constructor(public seoService: SeoService) {
    }

    ngOnInit(): void {
        this.seoService
            .setTitle('Live')
            .setDescription('Sport Live');
    }
}
