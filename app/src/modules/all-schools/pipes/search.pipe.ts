import { PipeTransform, Pipe } from '@angular/core';
import {AllTeamsSchoolsService} from "../../../+shared/services/all-teams-schools.service";
import * as _ from 'lodash';

@Pipe({name: 'search'})
export class SearchPipe implements PipeTransform {
    public searchText;
    public order;
    public loadedTeams;

    public searchTypes = {
        string: this.allTeamsSchoolsService.searchInString,
        array: this.allTeamsSchoolsService.searchInArray,
        number: this.allTeamsSchoolsService.searchNumber,
    };
    public searchConfig = {
        states: [{name: 'stateName', type: 'string'}, {name: 'stateShortName', type: 'string'}],
        cities: [{name: 'countyName', type: 'string'}, {name: 'countyShortName', type: 'string'}, {name: 'count_schools', type: 'number'}],
        schools: [{name: 'name', type: 'string'}],
    };
    public schoolsListSimple = {
        searchKeys: [{name: 'name', type: 'string'}]
    };
    public schools = {
        searchKeys: this.searchConfig.schools.concat(this.searchConfig.cities).concat(this.searchConfig.states),
    };
    public cities = {
        searchKeys: [],
        countParam: {name:'count_schools', func: this.calcFunc},
        contains: {key: 'schools', fetch: this.getSchools.bind(this), structure: this.schools}
    };
    public states = {
        searchKeys: this.searchConfig.states,
        contains: {key: 'county', structure: this.cities}
    };
    public schoolsChar = {
        searchKeys: [{name: 'count', type: 'number'}],
        contains: {key: 'county', fetch: this.getSchools.bind(this), structure: this.schoolsListSimple}
    };

    constructor(public allTeamsSchoolsService: AllTeamsSchoolsService) {
    }


    transform(data, order, searchText, loadedTeams?) {
        if (!searchText) {
            return data;
        }
        data = _.cloneDeep(data);
        this.loadedTeams = _.cloneDeep(loadedTeams);
        this.searchText = searchText.toLowerCase();
        this.order = order;
        switch (this.order) {
            case 'states' : return this.allTeamsSchoolsService.runSearch(data, this.states, searchText, this.searchTypes);
            case 'cities' : return this.allTeamsSchoolsService.runSearch(data, this.cities, searchText, this.searchTypes);
            case 'schools' : return this.allTeamsSchoolsService.runSearch(data, this.schoolsChar, searchText, this.searchTypes);
            case 'schoolsLevel' : return this.allTeamsSchoolsService.runSearch(data, this.schools, searchText, this.searchTypes);
            case 'schoolsListSimple' : return this.allTeamsSchoolsService.runSearch(data, this.schoolsListSimple, searchText, this.searchTypes);
        }
    }

    getSchools(dataKey) {
        if (this.order !== 'schools') {
            return this.loadedTeams[dataKey.countyId] ? this.loadedTeams[dataKey.countyId] : [];
        } else {
            return this.loadedTeams[dataKey.char.toLowerCase()] ? this.loadedTeams[dataKey.char.toLowerCase()] : [];
        }
    }

    calcFunc(level, countKey, containKey, containParams) {
        return level[containKey].length;
    }
}