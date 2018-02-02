import {Injectable} from '@angular/core';
import {AjaxService} from './ajax.service';
import {DatePipe} from '@angular/common';
import {RootService} from '../../modules/root/root.service';
import * as _ from 'lodash';
import {Game} from '../../models/game.model';
import {Observable} from "rxjs/Observable";
import {Observer} from "rxjs/Observer";

@Injectable()
export class SearchService {
    searchType: string;
    tagsList = [];
    searchResults;
    pagination;
    currentPage = 1;
    searchCounts;
    rootReady$ = this.rootService.ready$.filter(ready => ready);
    searchText: string;

    constructor(public ajaxService: AjaxService, public datePipe: DatePipe, public rootService: RootService) {

    }

    isCorrectDateFormat(str) {
        const dateObj = new Date(str);

        if ((str.split('/').length === 3 || str.trim().split(/\s+/).length >= 2) && dateObj.getFullYear() >= 1970) {
            return (str === this.datePipe.transform(Date.parse(str), 'M/d/yy') ||
                str === this.datePipe.transform(Date.parse(str), 'MMM d, y') ||
                str === this.datePipe.transform(Date.parse(str), 'MMM dd, y') ||
                str === this.datePipe.transform(Date.parse(str), 'MMMM d, y') ||
                str === this.datePipe.transform(Date.parse(str), 'MMMM dd, y') ||
                str.match(/^\d{2}([./-])\d{2}\1\d{4}$/) // matches
            );
        }

    }

    toSqlFormat(str) {
        const dateObj = new Date(str);
        return dateObj.toISOString().substring(0, 10);
    }

    getSearchResults() {
        this.searchResults = undefined;
        this.pagination = undefined;
        const data = {};
        data['q'] = this.getStrFromTags();
        data['page'] = this.currentPage;
        if (data['q']) {
            this.rootReady$.concatMap(ready => {
                data['seasonId'] = this.rootService.currentSeasonId;
                return this.ajaxService.post('search/' + this.searchType, data);
            }).subscribe((res) => {
                if (res.data) {
                    if (this.searchType === 'games') {
                        this.searchResults = [];
                        _.forEach(res.data, (game) => {
                            this.searchResults.push(new Game(game));
                        });
                    } else {
                        this.searchResults = res.data;
                    }
                    this.pagination = {
                        itemsPerPage: res.pagination.per_page,
                        currentPage: res.pagination.current_page,
                        totalItems: res.pagination.total
                    };
                }
            });
        }
    }

    getMediaResults(type?, page?) {
        this.searchResults = !type ? undefined : this.searchResults;
        this.pagination = !type ? undefined : this.pagination;
        const data = {};
        const req = this.rootReady$.concatMap(ready => {
            data['seasonId'] = this.rootService.currentSeasonId;
            data['q'] = this.getStrFromTags();
            data['page'] = (page) ? page : this.currentPage;
            data['mediaType'] = !type ? 'all' : type;

            if (data['q']) {
                return this.ajaxService.post('search/' + this.searchType, data)
            }
        });
        if(!type) {
            req.subscribe((res) => {
                    this.searchResults = res;
                    console.log(this.searchResults);
            });
        } else {
            console.log(this.searchResults);
            return req.do((res) => {
                   // debugger
                    this.searchResults[type].data = this.searchResults[type].data.concat(res[type].data);
                    this.searchResults[type].pagination = res[type].pagination;
            });
        }
    }

    getSearchCounts() {
        const data = {};
        data['seasonId'] = this.rootService.currentSeasonId;
        data['q'] = this.getStrFromTags();
        this.rootReady$.concatMap(ready => {
            data['seasonId'] = this.rootService.currentSeasonId;
            return this.ajaxService.post('search', data);
        }).subscribe((res) => {
            this.searchCounts = res;
        });

    }

    navSearchCounts() {
        const data = {};
        data['seasonId'] = this.rootService.currentSeasonId;
        data['q'] = this.getStrFromTags();
        return this.rootReady$.concatMap(ready => {
            data['seasonId'] = this.rootService.currentSeasonId;
            return this.ajaxService.post('search', data);
        });
    }


    getStrFromTags() {
        let searchText = '';
        if (this.tagsList.length) {
            _.forEach(this.tagsList, (v, k) => {
                searchText += v.val + ' ';
            });
        }
        return searchText;

    }

    search() {
        if (this.searchType === 'media') {
            this.getMediaResults();
        } else {
            this.getSearchResults();
        }
        this.getSearchCounts();
    }

    findDateInTags(tags) {
        _.forEach(tags, (val, index) => {
            if (tags[index + 1] && tags[index + 2]) {
                const dateStr = tags[index] + ' ' + tags[index + 1] + ' ' + tags[index + 2];
                if (this.isCorrectDateFormat(dateStr)) {
                    tags[index] = dateStr;
                    tags.splice(index + 1, 2);
                }
            }
        });
    }

    divideByTags() {
        if (this.searchText) {
            const tags = this.searchText.trim().split(/\s+/).filter((str) => {
                return str.length >= 2;
            });
            this.findDateInTags(tags);
            _.forEach(tags, (val, k) => {
                if (this.isCorrectDateFormat(val)) {
                    this.tagsList.push({name: val, val: this.toSqlFormat(val)});
                } else {
                    this.tagsList.push({name: val, val});
                }

                if (this.tagsList.length > 7) {
                    this.tagsList.pop();
                }
            });

        }

        this.updateTagsIds();

        this.searchText = '';
    }

    updateTagsIds() {
        _.forEach(this.tagsList, (val, k) => {
            val['id'] = k;
        });
    }


}
