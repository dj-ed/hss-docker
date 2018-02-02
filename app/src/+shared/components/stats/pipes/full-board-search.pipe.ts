import { Pipe, PipeTransform } from '@angular/core';
import {UserService} from "../../../services/user.service";
import {DatePipe} from "@angular/common";
import * as _ from 'lodash';
import * as Fuse from 'fuse.js';

@Pipe({
    name: 'fullBoardSearch'
})
export class FullBoardSearchPipe implements PipeTransform {
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

            {name: "team_name", wight: 0.5},
            {name: "county_name", weight: 0.5},
            {name: "city", weight: 0.5},

        ]
    };
    isSelectMode;
    searchText;

    constructor(public userService: UserService, public datePipe: DatePipe) {
    }


    transform(data, searchText, isSelectMode, rand) {
        this.searchText = searchText;
        this.isSelectMode = isSelectMode;
        return this.selectGames(_.cloneDeep(data));
    }


    selectGames(data) {
        let searcResult = [], i = 0;
        data.forEach((table, index) => {
            table.i = index;
            table.teams = table.teams.map((team, index) => {
                i += 1; team.index = i;
                return team;
            });
            let fuse, result = this.selectFavorites(table.teams);
            if (this.searchText) {
                fuse = new Fuse(result, this.fuseOptions);
                result = fuse.search(this.searchText);
            }
            if (result.length) {
                table.teams = result;
                searcResult = searcResult.concat(table);
            }
        });
        return searcResult;
    }

    selectFavorites(teams) {
        if (!this.isSelectMode) {
            return teams;
        }
        return teams.filter((team) => {
            return (this.userService.isFavorite('teams', team.team_id));
        });
    }

}