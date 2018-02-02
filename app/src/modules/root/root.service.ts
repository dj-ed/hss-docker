import { Inject, Injectable, PLATFORM_ID } from '@angular/core';
import { Season } from '../../models/season.model';
import { Sport } from '../../models/sport.model';
import { School } from '../../models/school.model';
import { AjaxService } from '../../+shared/services/ajax.service';
import { isPlatformBrowser, Location } from '@angular/common';
import { Gender } from '../../models/gender.model';
import * as _ from 'lodash';
import { Varsity } from '../../models/varsity.model';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { DomSanitizer } from '@angular/platform-browser';
import { AppCookieService } from "../../+shared/services/app-cookie.service";

@Injectable()
export class RootService {
    currentSeasonId: number;
    currentGenderId: number;
    currentSportId: number;
    currentVarsityId: string;
    ready$: BehaviorSubject<boolean> = new BehaviorSubject(false);
    sportChange$: BehaviorSubject<any> = new BehaviorSubject(false);
    seasonChange$: BehaviorSubject<any> = new BehaviorSubject(false);
    defaultSportId: number = 1;

    seasonList: Season[] = [];
    sportList: Sport[] = [];
    genderList: Gender[] = [];
    varsityList: Varsity[] = [];
    schoolList: School[] = [];

    constructor(public ajaxService: AjaxService, private location: Location,
                 @Inject(PLATFORM_ID) private platformId: object, public sanitizer: DomSanitizer, public cookie: AppCookieService) {
    }

    init() {
        this.ajaxService.post('root/current-variables', {
            url: this.location.path()
        }).subscribe(response => {
            this.seasonList = response.seasonList;
            this.sportList = response.sportList;
            this.schoolList = response.schoolList;
            this.genderList = response.genderList;
            this.varsityList = response.varsityList;
            this.currentSeasonId = +response.currentSeasonId;

            this.changeSport(response.currentSportId);
            if (response.currentGenderId) {
                this.changeGender(response.currentGenderId);
            }
            if (response.currentVarsityId) {
                this.changeVarsity(response.currentVarsityId);
            }
            this.ready$.next(true);
        });
    }

    loadCookies(variable, data) {
        if (!_.isUndefined(data)) {
            this[variable] = data;
        }
    }

    initBrowserCookies() {
        // Load Sport from Cookies
        const cookieSportId = this.cookie.get('currentSportId');
        if (cookieSportId) {
            this.currentSportId = +cookieSportId;
        }

        // Load Gender from Cookies
        const cookieGenderId = this.cookie.get('currentGenderId');
        if (cookieGenderId) {
            this.currentGenderId = +cookieGenderId;
        }

        // Load Varsity from Cookies
        const cookieVarsityId = this.cookie.get('currentVarsityId');
        if (cookieVarsityId) {
            this.currentVarsityId = cookieVarsityId.toString();
        }
    }

    seasonField(field: string = 'titleShort') {
        const season: Season = _.find(this.seasonList, ['id', this.currentSeasonId]);
        return season[field];
    }

    sportField(field: string = 'title') {
        const currentSport = this.currentSportId || this.defaultSportId;
        const sport: Sport = _.find(this.sportList, ['id', currentSport]);
        return sport[field];
    }

    sportByName(field: string = '') {
        return _.find(this.sportList, (sportItem) => sportItem.title.toLowerCase() === field.toLowerCase());
    }

    sportById(id: number) {
        return _.find(this.sportList, (sportItem) => sportItem.id === id);
    }

    /*
     * Generate Season URL like /$page/$season
     * @param page: string
     * @return array
     */
    seasonUrl(page) {
        const season: Season = _.find(this.seasonList, ['id', this.currentSeasonId]);
        return [page, season.titleShort];
    }

    /*
     * Generate Sport URL like /$page/$sport/$season/$gender
     * @param page: string
     * @param params: Object for routing
     * @return array
     */
    sportUrl(page: string, params?) {
        const sportId = this.currentSportId || this.defaultSportId;
        const sport: Sport = _.find(this.sportList, ['id', sportId]);
        const resultUrl = [page, sport.title.toLowerCase(), this.seasonField()];

        if (this.currentGenderId) {
            const gender = _.find(this.genderList, ['id', this.currentGenderId]);
            resultUrl.push(gender.name.toLowerCase());
        }

        if (params) {
            resultUrl.push(params);
        }
        return resultUrl;
    }

    changeSeason(seasonId) {
        if (seasonId !== this.currentSeasonId) {
            this.currentSeasonId = seasonId;
            this.seasonChange$.next(seasonId);
        }
    }

    changeSport(sportId: number) {
        if (sportId) {
            this.cookie.set('currentSportId', sportId);
            this.currentSportId = +sportId;
            this.sportChange$.next(sportId);
        } else {
            this.cookie.remove('currentSportId');
            this.currentSportId = undefined;
            this.sportChange$.next(sportId);
        }
    }

    changeGender(genderId?: number) {
        if (genderId) {
            this.cookie.set('currentGenderId', genderId);
            this.currentGenderId = +genderId;
        } else {
            this.cookie.remove('currentGenderId');
            this.currentGenderId = undefined;
        }
    }

    changeVarsity(varsityId?: string) {
        if (varsityId) {
            this.cookie.set('currentVarsityId', varsityId);
            this.currentVarsityId = varsityId;
        } else {
            this.cookie.remove('currentVarsityId');
            this.currentVarsityId = undefined;
        }
    }

    getVarsityId() {
        if (!this.currentVarsityId) {
            this.changeVarsity(this.varsityList[0].id);
        }
        return this.currentVarsityId;
    }

    isBrowser() {
        return isPlatformBrowser(this.platformId);
    }

    getSecureEmbed(url, params?) {
        const stringParams = params ? Object.keys(params).map(key => `&${key}=${params[key]}`).join('') : '';
        return this.sanitizer.bypassSecurityTrustHtml(`<iframe class="inner-media" allowfullscreen frameborder="0" controls src="${url}?rel=0${stringParams}" >
            </iframe>`);
    }

    getYouTubeId(url) {
        const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
        const match = url.match(regExp);
        return (match && match[7].length === 11) ? match[7] : '';
    }

    getTextInfo(url) {
        return this.ajaxService.post('texts/page', {url}, false);
    }

}
