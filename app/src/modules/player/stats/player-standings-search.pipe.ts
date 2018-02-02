import {Pipe, PipeTransform} from '@angular/core';
import * as _ from 'lodash';
import * as Fuse from 'fuse.js';
import {UserService} from "../../../+shared/services/user.service";

@Pipe({
    name: 'playerStandingsSearch'
})
export class PlayerStandingsSearchPipe implements PipeTransform {
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
            {name: "player.name", wight: 0.5},
            {name: "player.number", weight: 0.5},
            {name: "player.city", weight: 0.5},
        ]
    };
    section;
    isSelectMode;
    searchText;

    constructor(public userService: UserService) {
    }


    transform(data, searchText, isSelectMode, statsLength) {
        this.searchText = searchText;
        this.isSelectMode = isSelectMode;
        console.log(data);
        return this.selectGames(_.cloneDeep(data));

    }


    selectGames(data) {
        /*
        let searcResult = [], i = 0;
        data.forEach((table, index) => {
            table.i = index;
            table.stats = table.stats.map((row, index) => {
                i += 1;
                row.index = i;
                return row;
            });
            let fuse, result = this.selectFavorites(table.stats);
            if (this.searchText) {
                fuse = new Fuse(result, this.fuseOptions);
                result = fuse.search(this.searchText);
            }
            if (result.length) {
                table.stats = result;
                searcResult = searcResult.concat(table);
            }
        });
        console.log(searcResult);
        */
        return data;
    }


    selectFavorites(stats) {
        if (!this.isSelectMode) {
            return stats;
        }
        return stats.filter(row => this.userService.isFavorite('players', row.player.id));
    }

}