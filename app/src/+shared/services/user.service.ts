import {Injectable} from '@angular/core';
import {AjaxService} from "./ajax.service";
import {RootService} from "../../modules/root/root.service";
import * as _ from 'lodash';
import {User} from "../../models/user.model";
import {AppCookieService} from "./app-cookie.service";
import {BehaviorSubject} from "rxjs/BehaviorSubject";


@Injectable()
export class UserService {


    token: string;
    currentUser: User;
    favoritesList: any = [];
    scrapbookList: any = [];
    eventsList: any = [];
    updateScrapbook$: BehaviorSubject<any> = new BehaviorSubject([]);
    updateFavorites$: BehaviorSubject<any> = new BehaviorSubject([]);
    updateEvents$: BehaviorSubject<any> = new BehaviorSubject([]);

    constructor(public ajaxService: AjaxService, public rootService: RootService, public cookie: AppCookieService) {

    }

    isFavorite(modelType, id) {
        return !!_.find(this.favoritesList[modelType], {id});
    }

    isInScrapbook(modelType, id) {
        return !!_.find(this.scrapbookList[modelType], {id});
    }

    isAddedEvent(modelType, id) {
        return !!_.find(this.eventsList, {id});
    }

    setLike(modelType, modelId) {
        return this.ajaxService.post('likes/post-like', {modelId, modelType}, false).map(data => data.likes);
    }

    addToFavorite(modelType, modelId) {
        return this.ajaxService.post('favorites/post-favorite',
            {modelId, modelType: modelType.slice(0, -1), seasonId: this.rootService.currentSeasonId}, false)
            .subscribe((res) => {
                if (!res.success) {
                    return;
                }
                if (this.isFavorite(modelType, modelId)) {
                    _.remove(this.favoritesList[modelType], {user_id: this.currentUser.id, id: modelId});
                } else {
                    this.favoritesList[modelType].push({user_id: this.currentUser.id, id: modelId});
                }
                this.updateFavorites$.next(this.favoritesList);
            });
    }

    addToScrapbook(modelType, modelId) {
        return this.ajaxService.post('scrapbook/post-scrapbook',
            {modelId, modelType}, false)
            .subscribe((res) => {
                if (!res.success) {
                    return;
                }
                if (this.isInScrapbook(modelType, modelId)) {
                    _.remove(this.scrapbookList[modelType], {user_id: this.currentUser.id, id: modelId});
                } else {
                    this.scrapbookList[modelType].push({user_id: this.currentUser.id, id: modelId});
                }
                this.updateScrapbook$.next(this.scrapbookList);
            });
    }

    addToEvents(modelType, modelId) {
        return this.ajaxService.post('events/add-event', {modelId, modelType}, false)
            .subscribe((res) => {
                if (res.success && !this.isAddedEvent(modelType, modelId)) {
                    this.eventsList.push({user_id: this.currentUser.id, id: modelId});
                }
            });
    }

    getFavorites() {
        this.rootService.ready$.filter(ready => ready).concatMap(() => {
            return this.ajaxService.post('favorites/get-favorite', {seasonId: this.rootService.currentSeasonId});
        }).subscribe((res) => {
            this.favoritesList = res;
        });
    }


    getScrapbook() {
        this.ajaxService.post('scrapbook/get-scrapbook')
            .subscribe((res) => {
                this.scrapbookList = res;
            });
    }

    getEvents() {
        this.ajaxService.post('events/get-event')
            .subscribe((res) => {
                this.eventsList = res;
            });
    }

    loadCookies(authToken?: string) {
        if (authToken) {
            this.token = authToken;
            this.cookie.set('auth_token', authToken);

            this.ajaxService.post('auth/get-user', {
                token: authToken
            }).subscribe((user) => {
                if (user) {
                    this.currentUser = new User(user);
                    this.getFavorites();
                    this.getScrapbook();
                    this.getEvents();
                } else {
                    this.loadCookies();
                }
            });
        } else {
            this.cookie.remove('auth_token');
            this.token = null;
            delete this.currentUser;
        }
    }

    initBrowserCookies() {
        const authToken = this.cookie.get('auth_token') || '';
        if (authToken) {
            this.loadCookies(authToken.toString());
        }
    }

    login(email: string, password: string) {
        this.ajaxService.post('auth/login', {email, password}).subscribe(
            (response: any) => {
                if (response.token) {
                    this.loadCookies(response.token);
                    console.log('Login success... redirect?');
                } else {
                    console.log('auth token error');
                }
            },
            error => console.error(error)
        );
    }


    logout() {
        this.loadCookies();
        this.scrapbookList = [];
        this.favoritesList = [];
        this.eventsList = [];
        this.updateScrapbook$.next(this.scrapbookList);
        this.updateFavorites$.next(this.favoritesList);
        this.updateEvents$.next(this.eventsList);

    }

    isLoggedIn() {
        return (this.token && this.token.length !== 0);
    }
}