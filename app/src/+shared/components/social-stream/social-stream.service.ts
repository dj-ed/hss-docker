import { Injectable } from '@angular/core';
import { Observable } from 'rxjs/Observable';
import { HttpClient, HttpHeaders } from "@angular/common/http";

import * as _ from 'lodash';
import { environment } from '../../../../.env/environment';
import { AjaxService } from '../../services/ajax.service';
import 'rxjs/Rx';

@Injectable()
export class SocialService {

    constructor(public ajaxService: AjaxService, private http: HttpClient) {
    }

    get(url: string, paramsObj?: object): Observable<any> {
        const headers = new HttpHeaders();
        // Request params
        if (paramsObj && !_.isEmpty(paramsObj)) {
            const addParams = [];
            _.forEach(paramsObj, (val, key) => {
                const param = (typeof val === 'string') ? val : JSON.stringify(val);
                addParams.push(`${key}=${param}`);
            });
            url += '?' + addParams.join('&');
        }

        return this.http.get(url, {headers: headers});
    }


    getAccessFbToken() {
        return this.get('https://graph.facebook.com//oauth/access_token', {
            client_id: environment.FB_APP_ID,
            client_secret: environment.FB_APP_SECRET,
            grant_type: 'client_credentials'
        });
    }

    getFbLastPosts(page: string, limit: number) {
        page = page.replace('facebook.com/', '');
        return this.getAccessFbToken().concatMap(rez => {
            return this.get('https://graph.facebook.com/' + page + '/posts', {
                limit,
                fields: 'name,message,permalink_url,created_time,from,likes.limit(0).summary(1),comments.limit(0).summary(1),full_picture',
                access_token: rez.access_token
            }).map(result => {
                const finalRez = [];
                _.forEach(result.data, v => {
                    finalRez.push({
                        title: v.name,
                        description: v.message,
                        created_time: v.created_time,
                        likes_count: v.likes.summary.total_count,
                        comments_count: v.comments.summary.total_count,
                        permalink_url: v.permalink_url,
                        author: v.from.name,
                        image: v.full_picture,
                    });
                });

                return finalRez;
            });
        });
    }

    getTwLastPosts(page: string, limit: number) {
        page = page.replace('twitter.com/', '');

        return this.ajaxService.post('home/twitter', {count: limit, page}).map(rez => {
            const finalRez = [];
            _.forEach(rez, v => {
                finalRez.push({
                    text: v.text,
                    createdTime: v.createdAt,
                    likesCount: v.favorite_count,
                    repostCount: v.retweet_count,
                    author: v.user.name,
                    image: (v.entities.media) ? v.entities.media[0].media_url : null,
                    hashtags: v.entities.hashtags,
                    url: 'https://twitter.com/statuses/' + v.id_str,
                });
            });
            return finalRez;
        });
    }
}
