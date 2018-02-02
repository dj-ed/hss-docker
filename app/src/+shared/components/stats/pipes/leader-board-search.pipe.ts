import { Pipe, PipeTransform } from '@angular/core';
import {UserService} from "../../../services/user.service";
import * as _ from 'lodash';
import * as Fuse from 'fuse.js';

@Pipe({
    name: 'leaderBoardSearch'
})
export class LeaderBoardSearchPipe implements PipeTransform {
    fuseOptions: any = {
        shouldSort: true,
        threshold: 0.2,
        location: 0,
        distance: 1,
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
        return searchText || isSelectMode ?  this.selectGames(_.cloneDeep(data)) : data;
    }


    selectGames(data) {
       return  data.map((table) => {
            if (this.searchText) {
                const fuse = new Fuse(table.stats, this.fuseOptions);
                const firstPlayer = table.stats[0];
                const searchResult = fuse.search(this.searchText);
                table.stats = searchResult.length ? [firstPlayer].concat(searchResult) : [];
            }
            table.stats = this.selectFavorites(table.stats).length ? table.stats : [];
            return table.stats.length ? table : null;
        }).filter((res) => res);

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