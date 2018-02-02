import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { Observable } from 'rxjs/Observable';
import * as _ from 'lodash';


import 'rxjs/add/operator/map';
import 'rxjs/add/operator/catch';
import 'rxjs/add/observable/throw';
import 'rxjs/add/observable/of';

import { environment }from '../../../.env/environment';
import { HttpClient, HttpHeaders } from "@angular/common/http";
import { URLSearchParams } from "@angular/http";
import { TransferState, makeStateKey } from '@angular/platform-browser';
import { AppCookieService } from "./app-cookie.service";


@Injectable()
export class AjaxService {

    public baseUrl = environment.API_URL;

    constructor(private http: HttpClient,
                public router: Router,
                private state: TransferState,
                public cookie: AppCookieService) {


    }

    /*
     * AJAX - POST method
     *
     * @param url:string - url for request
     * @param paramsObj: json - optional params
     * @return Observable
     */
    post(url: string, paramsObj?: object, store = true): Observable<any> {
        const params = new URLSearchParams();
        const headers = {'Content-Type': 'application/x-www-form-urlencoded'};
        const isAuth = this.cookie.get('auth_token');
        let stateKey;
        // Auth header
        if (isAuth) {
            headers['Authorization'] = 'Bearer ' + isAuth;
        }

        // Request params
        let urlHash = url;
        if (paramsObj && !_.isEmpty(paramsObj)) {
                _.forEach(paramsObj, (val, key) => params.set(key, val));
                urlHash += this.generateHash(JSON.stringify(paramsObj));
            }
        if (store) {
            stateKey = makeStateKey(urlHash);
            let  stateTransferData = this.state.get(stateKey, null as any);

            // Return from store
            if (stateTransferData) {
                return Observable.of(stateTransferData);
            }
        }
        return this.http.post(this.baseUrl + url, params.toString(), {headers: new HttpHeaders(headers)})
            .map(res =>  this.handleSuccess(res, stateKey))
            .catch(this.handleError.bind(this));
    }


    /*
     * AJAX - POST method with file
     *
     * @param url:string - url for request
     * @param paramsObj: json - optional params
     * @return Observable
     */
    file(url: string, paramsObj?: object): Observable<any> {
        const params = new FormData();
        const headers = {enctype: 'multipart/form-data'};
        const isAuth = this.cookie.get('auth_token');

        // Auth header
        if (isAuth) {
            headers['Authorization'] = 'Bearer ' + isAuth;
        }

        // Request params
        if (paramsObj && !_.isEmpty(paramsObj)) {
            _.forEach(paramsObj, (val, key) => {
                if (!_.isUndefined(val)) {
                    params.append(key, val);
                }
            });
        }
        let stateKey = makeStateKey(url),
            stateTransferData = this.state.get(stateKey, null as any);
        // Return from store
        if (stateTransferData) {
            return Observable.of(stateTransferData);
        }

        return this.http.post(this.baseUrl + url, params, {headers: new HttpHeaders(headers)})
            .map(res => this.handleSuccess(res, url))
            .catch(this.handleError.bind(this));
    }

    // Get success results
    handleSuccess(res, stateKey?) {
        if (!_.isEmpty(res) &&  stateKey) {
            this.state.set(stateKey, res as any);

        }
        return res;
    }

    // Get error results
    handleError(error) {
        switch (error.status) {
            case 400:
                console.log('Show error popup');
                break;
            case 401:
                console.log('Go to login page');
                break;
            case 409:
            case 404:
                console.log('Go not-found page');
                this.router.navigate(['/not-found']);
                break;
        }

        return Observable.throw(error);
    }

    generateHash(text: string) {
        let hash = 0;
        if (text.length === 0) {
            return hash;
        }
        for (let i = 0; i < text.length; i++) {
            const char = text.charCodeAt(i);
            hash = ((hash << 5) - hash) + char;
            hash = hash & hash;
        }
        return hash;
    }

}
