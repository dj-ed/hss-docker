import { Component, OnInit, Renderer2, ViewEncapsulation } from '@angular/core';
import { RootService } from './root.service';
import { UserService } from '../../+shared/services/user.service';

import { Router } from '@angular/router';
import { HeaderService } from './root-header/root-header.service';
import { AppCookieService } from "../../+shared/services/app-cookie.service";

@Component({
    selector: 'app-root',
    templateUrl: './root.component.html',
    styleUrls: [
        '../../styles/normalize.scss',
        '../../styles/main.scss',
        './root.component.scss',
    ],
    encapsulation: ViewEncapsulation.None,
    providers: [HeaderService],
})
export class RootComponent implements OnInit {

    constructor(public rootService: RootService, public userService: UserService, private renderer: Renderer2,
                public router: Router, public headerService: HeaderService, public cookie: AppCookieService) {
    }

    ngOnInit(): void {
        /*
         if(!this.rootService.isBrowser()){
         console.log(this.cookie.getAll());
         console.log('server!');
         }else{
         console.log(this.cookie.getAll());
         console.log('client!');
         }
         */
        // Show submenu when load home page first time
        this.headerService.init();

        // Init Cookie variables
        this.userService.initBrowserCookies();
        this.rootService.initBrowserCookies();


        // this.userService.login('admin@gmail.com', '123123123');
        // this.userService.login('mcdanielg@flaglerschools.com', '123123123');

        // Init Root variables
        this.rootService.init();
    }

    onDeactivate() {
        window.scrollTo(0, 0);
    }

}
