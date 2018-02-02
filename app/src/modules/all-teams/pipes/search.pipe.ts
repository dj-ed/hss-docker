import {PipeTransform, Pipe} from '@angular/core';
import * as _ from 'lodash';
import {AllTeamsSchoolsService} from "../../../+shared/services/all-teams-schools.service";

@Pipe({name: 'search'})
export class SearchPipe implements PipeTransform {
    public searchText;
    public order;
    public loadedTeams;

    public searchType = {
        string: this.allTeamsSchoolsService.searchInString,
        array: this.allTeamsSchoolsService.searchInArray,
        number: this.allTeamsSchoolsService.searchNumber,
    };
    public searchConfig = {
        states: [{name: 'stateName', type: 'string'}, {name: 'stateShortName', type: 'string'}],
        cities: [{name: 'countyName', type: 'string'}, {name: 'countyShortName', type: 'string'}],
        sports: [{name: 'title', type: 'string'}],
        schools: [{name: 'schoolName', type: 'string'}, {name: 'leagues', type: 'array'}, {
            name: 'count',
            type: 'number'
        }, {name: 'sportTitle', type: 'string'}]
    };
    public schools = {
        searchKeys: this.searchConfig.schools.concat(this.searchConfig.cities).concat(this.searchConfig.states),
        countParam: {name: 'count', func: this.calcTeams.bind(this)}
    };
    public sports = {
        searchKeys: this.searchConfig.sports.concat(this.searchConfig.cities).concat(this.searchConfig.states),
        countParam: {name: 'count', func: this.calcFunc},
        contains: {key: 'schools', structure: this.schools}
    };
    public cities = {
        searchKeys: this.searchConfig.cities.concat(this.searchConfig.states),
        countParam: {name: 'count', func: this.calcFunc},
        contains: {key: 'sports', structure: this.sports}
    };
    public states = {
        searchKeys: this.searchConfig.states,
        countParam: {name: 'count', func: this.calcFunc},
        contains: {key: 'county', structure: this.cities}
    };

    constructor(public allTeamsSchoolsService: AllTeamsSchoolsService) {
    }


    transform(data, order, searchText, loadedTeams?, schoolId?) {
        if (!searchText) {
            return data;
        }
        data = _.cloneDeep(data);
        this.loadedTeams = _.cloneDeep(loadedTeams);
        this.searchText = searchText.toLowerCase();
        this.order = order;
        return this.order !== 'teams' ? this.allTeamsSchoolsService.runSearch(data, this[order], searchText, this.searchType)
            : this.searchInTeams(schoolId, this.searchText);
    }

    searchInTeams(schoolId, searchText): any {
        let res = [];
        if (this.loadedTeams[schoolId]) {
            res = this.loadedTeams[schoolId].map((team) => {
                if (team.genderName.toLowerCase().indexOf(searchText) === 0 ||
                    team.teamType.toLowerCase().indexOf(searchText) === 0 ||
                    team.leagues.toLowerCase().indexOf(searchText) === 0) {
                    return team;
                } else {
                    return null;
                }
            }).filter(team => team);
            return res.length ? res : this.loadedTeams[schoolId];
        } else {
            return res;
        }
    }

    calcTeams(level, param, searchText) {
        const res = level.leagues.filter(liga => {
            return this.allTeamsSchoolsService.searchInString(liga, this.searchText);
        }).length;
        return res ? res : level.count

    }

    calcFunc(level, param) {
        let count = 0;
        level[param.contains.key].forEach((levelItem) => {
            if (param.contains.structure.countParam) {
                count += levelItem[param.contains.structure.countParam.name];
            }
        });
        return count;
    }

}
