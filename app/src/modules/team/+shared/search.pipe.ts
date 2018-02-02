import {PipeTransform, Pipe} from '@angular/core';
import * as _ from 'lodash';
import * as Fuse from 'fuse.js';
import {UserService} from "../../../+shared/services/user.service";

@Pipe({name: 'searchPlayers'})
export class SearchPipe implements PipeTransform {

    public fuseOptions = {
            shouldSort: true,
            upportHighlight: true,
            threshold: 0.3,
            location: 0,
            distance: 1,
            maxPatternLength: 32,
            minMatchCharLength: 1,
            minSearchTermLength: 1,
            fusejsHighlightKey: 'fuseJsHighlighted',
            fusejsScoreKey: 'fuseJsScore',
            keys: [{name: 'firstName', weight: 0.7}, {name: 'lastName', weight: 0.7}, {name: 'number', weight: 0.2}, {name: 'positions', weight: 0.2}]};
    isSelectMode;
    searchText;

    constructor(public userService: UserService) {

    }

    transform(data: any, searchText, isSelectMode): any {
        let searcResult = data;
        const fuse = new Fuse(searcResult, this.fuseOptions);

        this.isSelectMode = isSelectMode;
        this.searchText = searchText;
        if (this.searchText) {
            searcResult = fuse.search(searchText);
        }
        return _.chain(searcResult).filter(this.selectFavorites.bind(this)).value();
    }


    selectFavorites(player) {
        if (!this.isSelectMode) {
            return true;
        }

        return this.userService.isFavorite('players', player.id);
    }

}
