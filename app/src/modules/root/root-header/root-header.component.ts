import { Component, ElementRef, OnInit, ViewChild, ViewEncapsulation } from '@angular/core';
import { RootService } from '../root.service';
import { Router } from '@angular/router';
import * as _ from 'lodash';
import { Season } from '../../../models/season.model';
import { Gender } from '../../../models/gender.model';
import { Sport } from '../../../models/sport.model';
import { Varsity } from '../../../models/varsity.model';
import { Observable } from 'rxjs/Rx';
import { HeaderService } from './root-header.service';

@Component({
    selector: 'root-header',
    templateUrl: './root-header.component.html',
    styleUrls: ['./root-header.component.scss'],

})
export class RootHeaderComponent implements OnInit {
    navSeason: Season;
    navGender: Gender;
    navSport: Sport;
    navVarsity: Varsity;

    constructor(public rootService: RootService, public router: Router, public headerService: HeaderService) {
    }

    ngOnInit(): void {
        if (this.rootService.isBrowser()) {
            Observable.fromEvent(document, 'click').subscribe((event: any) => {
                // close main menu
                if (!event.target.closest('.main-menu') && !event.target.closest('.burger')) {
                    this.headerService.mainMenuOpened = false;
                }

                // close top submenu
                if (!event.target.closest('.submenu') && !event.target.closest('.submenu-drop')) {
                    this.headerService.topSubMenuOpened = false;
                }

                // close location menu
                if (!event.target.closest('.location-menu') && !event.target.closest('.location-drop')) {
                    this.headerService.locationMenuOpened = false;
                }

                // close season menu
                if (!event.target.closest('.prev-season') && !event.target.closest('.season-drop')) {
                    this.headerService.seasonsMenuOpened = false;
                }

                // close search
                if (!event.target.closest('.top_search') && !event.target.closest('.search-button')) {
                    this.headerService.searchOpen = false;
                }
            });
        }
    }

    toggleMainMenu() {
        this.headerService.toggleMainMenu();
        if (this.headerService.mainMenuOpened) {
            this.updateNavVariables();
        }
    }

    updateNavVariables() {
        this.headerService.varsityVisible = !_.isUndefined(this.rootService.currentGenderId);
        this.navSeason = _.find(this.rootService.seasonList, ['id', this.rootService.currentSeasonId]);
        this.navGender = _.find(this.rootService.genderList, ['id', this.rootService.currentGenderId]);
        this.navSport = _.find(this.rootService.sportList, ['id', this.rootService.currentSportId]);
        this.navVarsity = _.find(this.rootService.varsityList, ['id', this.rootService.currentVarsityId]);
    }

    changeSeason(season) {
        this.rootService.changeSeason(season.id);
        this.headerService.toggleSeasonMenu();
        this.updateNavVariables();

        this.router.navigate(this.rootService.seasonUrl('season')).then(() => {
            this.rootService.init();
        });
    }

    changeSport(sportId?) {
        this.rootService.changeSport(sportId);
        this.headerService.toggleTopSubMenu();
        this.updateNavVariables();

        if (sportId) {
            this.router.navigate(this.rootService.sportUrl('/sport/overview'));
        } else {
            this.router.navigate(this.rootService.seasonUrl('/season'));
        }
    }

    changeGender(genderId?) {
        this.rootService.changeGender(genderId);
        this.updateNavVariables();
        if (this.navSport) {
            this.router.navigate(this.rootService.sportUrl('/sport/overview'));
        } else {
            this.router.navigate(this.rootService.seasonUrl('/season'));
        }
    }

    changeVarsity(varsityId?) {
        this.rootService.changeVarsity(varsityId);
        this.updateNavVariables();
    }

    getSportTitleId() {
        switch (this.rootService.currentSportId) {
            case 1: {
                return 'vb';
            }
            case 2: {
                return 'bb';
            }
            default: {
                return 's_all';
            }
        }
    }

    getSportTitle() {
        if (this.rootService.currentSportId) {
            return this.rootService.sportField();
        }
        return 'OVERVIEW';
    }

    setRootVariables() {
        this.rootService.changeSeason(this.navSeason.id);
        this.rootService.changeGender(this.navGender ? this.navGender.id : undefined);
        this.rootService.changeSport(this.navSport ? this.navSport.id : undefined);
        this.rootService.changeVarsity(this.navVarsity ? this.navVarsity.id : undefined);
    }

    buildSportUrl(baseUrl) {
        const resultUrl = [baseUrl, this.navSport.title.toLowerCase(), this.navSeason.titleShort];
        if (this.navGender) {
            resultUrl.push(this.navGender.name.toLowerCase());
        }

        return resultUrl;
    }

    isLastSeason() {
        const lastSeason = _.first(this.rootService.seasonList);
        return this.rootService.currentSeasonId === lastSeason.id;
    }
}
