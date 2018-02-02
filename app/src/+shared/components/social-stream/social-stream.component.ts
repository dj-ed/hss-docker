import { Component, Input, OnChanges, PLATFORM_ID, Inject } from '@angular/core';
import { isPlatformBrowser } from '@angular/common';
import { SocialService } from './social-stream.service';
import {environment} from '../../../../.env/environment';

@Component({
    selector: 'social-stream',
    templateUrl: './social-stream.component.html',
    styleUrls: ['./social-stream.component.scss'],
    providers: [SocialService],
})
export class SocialStreamComponent implements OnChanges {

    fbPosts: object;
    twPosts: object;

    @Input('socials') socials: object;

    constructor(public socialService: SocialService, @Inject(PLATFORM_ID) private platformId: object) {
    }

    getFbPage() {
        if (this.socials && this.socials['fb']) {
            return this.socials['fb'];
        }
        return environment.FB_PAGE;
    }

    getTwPage() {
        if (this.socials && this.socials['tw']) {
            return this.socials['tw'];
        }
        return environment.TW_PAGE;
    }

    ngOnChanges(changes) {
        if (changes.socials) {
            this.getFbPosts();
            this.getTwPosts();
        }
    }

    getFbPosts() {
        if (isPlatformBrowser(this.platformId)) {
            this.socialService.getFbLastPosts(this.getFbPage(), 3).subscribe(rez => {
                if (!rez.length) {
                    this.socials['fb'] = environment.FB_PAGE;
                } else {
                    this.fbPosts = rez;
                }

            });
        }
    }

    getTwPosts() {
        if (isPlatformBrowser(this.platformId)) {
            this.socialService.getTwLastPosts(this.getTwPage(), 3).subscribe(rez => {
                if (!rez.length) {
                    this.socials['tw'] = environment.TW_PAGE;
                } else {
                    this.twPosts = rez;

                }
            });
        }
    }

}
