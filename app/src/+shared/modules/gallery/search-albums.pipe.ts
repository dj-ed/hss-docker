import {PipeTransform, Pipe} from '@angular/core';
import {DatePipe} from "@angular/common";
import * as Fuse from 'fuse.js';
import {UserService} from "../../services/user.service";

@Pipe({name: 'searchAlbum'})
export class SearchAlbums implements PipeTransform {

    fuseOptions = {
        shouldSort: true,
        threshold: 0.2,
        location: 0,
        distance: 10,
        maxPatternLength: 32,
        minMatchCharLength: 1,
        minSearchTermLength: 1,
        fusejsHighlightKey: 'fuseJsHighlighted',
        fusejsScoreKey: 'fuseJsScore',
        keys:
            [{name: 'gameData.team.shortName', weight: 0.7}, {name: 'gameData.opponentTeam.shortName', weight: 0.7}, {name: 'index', weight: 0.2},
                {name: 'gameData.gameType', weight: 0.2}]};
    isSelectMode;
    searchText;

    constructor(public datePipe: DatePipe, public userService: UserService) {

    }

    transform(data: any, searchText, isSelectMode): any {
        /*
        'M/d/yy' (e.g. 6/15/15)
        'MMM d, y' (e.g. Jun 15, 2015)
        'MMMM d, y' (e.g. June 15, 2015)
        ‘dd-MM-YY’ (e.g. 22-03-1981)
        `dd.MM.YY` (e.g. 22.03-1981)
        `dd.MM.YY` (e.g. 22.03.1981)
         */

        this.isSelectMode = isSelectMode;
        this.searchText = searchText;

        let date, filteredBYFavorite = data.filter(this.selectFavorites.bind(this));

        if (!searchText) {
            return filteredBYFavorite;
        }
        if (searchText === this.datePipe.transform(Date.parse(searchText), 'M/d/yy') ||
            searchText === this.datePipe.transform(Date.parse(searchText), 'MMM d, y') ||
            searchText === this.datePipe.transform(Date.parse(searchText), 'MMMM d, y') ||
            searchText.match(/^\d{2}([./-])\d{2}\1\d{4}$/) // matches
        ) {
            date = searchText.match(/^\d{2}([./-])\d{2}\1\d{4}$/) ? Date.parse(searchText.slice(3, 5) + '.' + searchText.slice(0, 2) + '.' + searchText.slice(5)) : Date.parse(searchText);
        }
        if (date) {
            return  filteredBYFavorite.filter(album => album.date >= date);
        } else  {
            const fuse = new Fuse(filteredBYFavorite, this.fuseOptions);
            return fuse.search(searchText);
        }
    }


    selectFavorites(album) {
        if (!this.isSelectMode) {
            return true;
        }
        console.log(this.userService.scrapbookList);
        console.log(album.id);
        return this.userService.isInScrapbook('album', album.id)
    }
}
