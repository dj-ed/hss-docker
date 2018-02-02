import { Injectable } from '@angular/core';
import { AppCookieService } from "../../../+shared/services/app-cookie.service";
import { Location } from "@angular/common";
import { AjaxService } from "../../../+shared/services/ajax.service";
import { RootService } from "../root.service";

@Injectable()
export class HeaderService {
    mainMenuOpened: boolean = false;
    topSubMenuOpened: boolean = false;
    locationMenuOpened: boolean = false;
    seasonsMenuOpened: boolean = false;
    searchOpen: boolean = false;
    varsityVisible: boolean = false;

    constructor(public rootService: RootService, public ajaxService: AjaxService, private location: Location, public  cookie: AppCookieService) {
    }

    getLocationTeams() {
        return this.rootService.ready$.filter(ready => ready).concatMap(() => {
            return this.ajaxService.post('location/teams', {seasonId: this.rootService.currentSeasonId})
        });
    }

    getLocationState() {
        return this.rootService.ready$.filter(ready => ready).concatMap(() => {
            return this.ajaxService.post('location/states', {seasonId: this.rootService.currentSeasonId})
        });
    }

    getLocationCity() {
        return this.rootService.ready$.filter(ready => ready).concatMap(() => {
            return this.ajaxService.post('location/cities', {seasonId: this.rootService.currentSeasonId})
        });
    }

    getLocationZip(zipCode) {
        return this.rootService.ready$.filter(ready => ready).concatMap(() => {
            return this.ajaxService.post('location/zip-code', {seasonId: this.rootService.currentSeasonId, zipCode: zipCode})
        });
    }

    init() {
        // Show submenu on home page if first visit
        if (this.rootService.isBrowser()) {
            if (!this.location.path().length && !this.cookie.get('show_home_submenu')) {
                this.topSubMenuOpened = true;
            }
            this.cookie.set('show_home_submenu', 1);
        }
    }

    toggleMainMenu() {
        this.mainMenuOpened = !this.mainMenuOpened;
    }

    toggleLocationMenu() {
        this.locationMenuOpened = !this.locationMenuOpened;
    }

    toggleSeasonMenu() {
        this.seasonsMenuOpened = !this.seasonsMenuOpened;
    }

    toggleTopSubMenu() {
        this.topSubMenuOpened = !this.topSubMenuOpened;
    }

    toogleSearch() {
        this.searchOpen = !this.searchOpen;

    }

}
