import { Pipe, PipeTransform } from '@angular/core';
import {UserService} from "../../../services/user.service";
import * as _ from 'lodash';
import * as Fuse from 'fuse.js';

@Pipe({
    name: 'teamStandingsSearch'
})
export class TeamStandingsSearchPipe implements PipeTransform {
    fuseOptions: any = {
        shouldSort: true,
        threshold: 0.2,
        location: 0,
        distance: 10,
        maxPatternLength: 32,
        minMatchCharLength: 1,
        minSearchTermLength: 1,
        fusejsHighlightKey: 'fuseJsHighlighted',
        fusejsScoreKey: 'fuseJsScore',
        keys: [
            {name: "first_name", wight: 0.5},
            {name: "last_name", weight: 0.5},
            {name: "number", weight: 0.5},
        ]
    };
    section;
    isSelectMode;
    searchText;

    constructor(public userService: UserService) {
    }


    transform(data, searchText, isSelectMode) {
        this.searchText = searchText;
        this.isSelectMode = isSelectMode;
        return this.selectGames(_.cloneDeep(data));
    }


    selectGames(data) {
        let searcResult = this.selectFavorites(data);
        const fuse = new Fuse(searcResult, this.fuseOptions);
        if (this.searchText) {
            searcResult = fuse.search(this.searchText);
        }
        return searcResult;
    }

    selectFavorites(players) {
        if (!this.isSelectMode) {
            return players;
        }
        return players.filter((player) => {
            return this.userService.isFavorite('players', player.player_id);
        });
    }

}