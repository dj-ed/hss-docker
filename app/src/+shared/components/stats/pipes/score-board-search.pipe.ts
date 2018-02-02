import {Pipe, PipeTransform} from '@angular/core';
import * as _ from 'lodash';
import * as Fuse from 'fuse.js';
import {UserService} from "../../../services/user.service";
import {DatePipe} from "@angular/common";


@Pipe({
    name: 'scoreBoardSearch'
})
export class ScoreBoardSearchPipe implements PipeTransform {
    fuseOptions: any = {
        shouldSort: true,
        threshold: 0.4,
        location: 0,
        distance: 100,
        maxPatternLength: 32,
        minMatchCharLength: 1,
        minSearchTermLength: 1,
        fusejsHighlightKey: 'fuseJsHighlighted',
        fusejsScoreKey: 'fuseJsScore',
        keys: [
            {name: "gameType", weight: 0.2},
            {name: "opponentTeam.name", wight: 0.5},
            {name: "team.name", wight: 0.5}
        ]
    };
    section;
    isSelectMode;
    searchText;

    constructor(public userService: UserService, public datePipe: DatePipe) {
    }


    transform(data, searchText, section, isSelectMode) {
        this.section = section;
        this.searchText = searchText;
        this.isSelectMode = isSelectMode;
        let date;

        if (searchText) {
            if (this.searchText) {
                if (searchText === this.datePipe.transform(Date.parse(searchText), 'M/d/yy') ||
                    searchText === this.datePipe.transform(Date.parse(searchText), 'MMM d, y') ||
                    searchText === this.datePipe.transform(Date.parse(searchText), 'MMMM d, y') ||
                    searchText.match(/^\d{2}([./-])\d{2}\1\d{4}$/) // matches
                ) {
                    date = searchText.match(/^\d{2}([./-])\d{2}\1\d{4}$/) ? Date.parse(searchText.slice(3, 5) + '.' + searchText.slice(0, 2) + '.' + searchText.slice(5)) : Date.parse(searchText);
                }
            }
        }
        return this.selectGames(_.cloneDeep(data), date);
    }


    selectGames(data, date) {
        let searcResult = [];
        let i = 0;
        data.forEach((table) => {
            table.games = table.games.map((game, index) => {
                i += 1;
                game.index = i;
                return game;
            });
            let fuse, result = this.selectFavorites(table.games);
            if (!date && this.searchText) {
                fuse = new Fuse(result, this.fuseOptions);
                result = fuse.search(this.searchText);
            } else if (date) {
                result = result.filter(game => {
                    return Date.parse(game.dateObj()) >= date;
                });
            }
            if (result.length) {
                table.games = result;
                searcResult = searcResult.concat(table);
            }
        });

        return searcResult;
    }

    selectFavorites(games) {
        if (!this.isSelectMode) {
            return games;
        }
        return games.filter((game) => {
            if (this.section) {
                return (this.userService.isFavorite('teams', game.opponentTeam.id));
            } else {
                return (this.userService.isFavorite('teams', game.opponentTeam.id)) ||
                        this.userService.isFavorite('teams', game.team.id);
            }
        });
    }


}