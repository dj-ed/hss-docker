import { Component, Input, OnInit } from '@angular/core';
import { environment } from '../../../../.env/environment';
import { RootService } from "../../../modules/root/root.service";

@Component({
    selector: 'share-component',
    templateUrl: './share.component.html',
    styleUrls: ['./share.component.scss']
})
export class ShareComponent implements OnInit {
    @Input() types: any[]; // fb, tw, gp
    @Input() title?: string;
    @Input() text: string;
    url: string;
    fbAppId: string;

    constructor(private rootService: RootService) {

    }

    ngOnInit() {
        if (this.rootService.isBrowser()) {
            this.url = document.location.href;
        }
        this.fbAppId = environment.FB_APP_ID;
    }

    isVisible(type) {
        return this.types.indexOf(type) !== -1;
    }

}
