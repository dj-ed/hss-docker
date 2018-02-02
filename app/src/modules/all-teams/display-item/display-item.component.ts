import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {RootService} from "../../root/root.service";
import {AllTeamsService} from "../all-teams.service";
import * as _ from 'lodash';
import {SearchPipe} from "../pipes/search.pipe";
import {AllTeamsSchoolsService} from "../../../+shared/services/all-teams-schools.service";
import { apperaAnimation } from './display-item.animation';
import {animate, keyframes, state, style, transition, trigger} from "@angular/animations";

@Component({
    selector: 'display-item',
    templateUrl: './display-item.component.html',
    styleUrls: ["../../../styles/all-schools-teams.scss", "./display-item.component.scss"],
    animations: [apperaAnimation,
        trigger('showIn', [
            state('active', style({transform: 'translateX(0)'})),
            transition('inactive => active', [
                animate(3000, keyframes([
                    style({background: 'red'}),
                    style({background: 'yellow'}),
                    style({background: 'green'})
                ]))
            ])
            ])
    ],
})
export class DisplayItemComponent implements OnInit {
    @Input('config') set setConfig(config) {
        this.config = this.config ? this.config : config;
        this.config.viewType = config.viewType;
        this.config.data = _.cloneDeep(this.config.data);
    };
    config: {
        fullData: any,
        viewType: string,
        order: string,
        index: any,
        data,
        firstLoadData: { firstState: any, firstCounty: any, firstSport: any, firstSchool: any, firstLoad: boolean },
        params?: { states: any[], counties: any[], sports: any[], schools: any[], data: any[], scrollAnimation: any },
        isFirstLoadState: boolean,
        globalSearchText: string,
        changeOrder$
    };
    isReadyRoot = false;
    @Output() scrollToChar: EventEmitter<any> = new EventEmitter();
    @Output() searchModeEnabled: EventEmitter<any> = new EventEmitter();
    @Output() searchModeDisabled: EventEmitter<any> = new EventEmitter();
    savedData;
    searchText;
    l = _;

    constructor(public rootService: RootService, public allTemsService: AllTeamsService, public searchPipe: SearchPipe, public allTeamsSchoolsService: AllTeamsSchoolsService) {
        this.rootService.ready$.subscribe((ready) => this.isReadyRoot = ready);
    }

    toggleState(stateId, open?) {
        const foundIndex = _.indexOf(this.config.params.states, stateId);
        if (foundIndex === -1) { // open
            this.config.params.states.push(stateId);
        } else if (foundIndex !== -1 && !open) { // close
            this.config.params.states.splice(foundIndex, 1);
            this.config.data.county.forEach(county => this.toggleCounty(county.countyId, false, true));
        }
    }
    isEqual(arg1 , arg2) {
        console.log(_.isEqual(arg1, arg2));
        return _.isEqual(arg1, arg2);
    }

    toggleCounty(countyId, open?, close?) {
        const foundIndex = _.indexOf(this.config.params.counties, countyId);
        if (foundIndex === -1 && !close) { // open
            this.config.params.counties.push(countyId);
        } else if (foundIndex !== -1 && !open || foundIndex !== -1 && close && !open) { // close
            this.config.params.counties.splice(foundIndex, 1);
            this.toggleSport(countyId, null, false, true);
        }
    }

    toggleSport(countyId, sportId, open?, closeAll?) {
        const foundIndex = _.findIndex(this.config.params.sports, {sportId, countyId});
        if (foundIndex === -1 && !closeAll) { // open
            this.config.params.sports.push({countyId, sportId});
        } else if (foundIndex !== -1 && !open && foundIndex !== -1 && !closeAll && !open) { // closeOne
            this.config.params.sports.splice(foundIndex, 1);
            _.remove(this.config.params.schools, school => school.sportId === sportId && school.countyId === countyId);
        } else if (closeAll) { // closeAll
            this.config.params.sports = this.config.params.sports.filter(sport => {
                _.remove(this.config.params.schools, school => school.sportId === sport.sportId && school.countyId === countyId);
                return sport.countyId !== countyId;
            });
        }
    }

    toggleSchool(params: { countyId: any, sportId: any, schoolId: any, statesId: any }, open?) {
        const foundIndex = _.findIndex(this.config.params.schools, params);
        if (foundIndex === -1) { // open
            if (!this.config.params.data[params.schoolId]) { // load schoolData
                this.allTemsService.loadSchoolTeams(params.schoolId).subscribe((res) => {
                    this.config.params.data[params.schoolId] = res.data;
                });
            }
            this.config.params.schools.push(params);
            this.toggleState(params.statesId, true);
            this.toggleCounty(params.countyId, true);
            this.toggleSport(params.countyId, params.sportId, true);
        } else if (foundIndex !== -1 && !open) { // close
            this.config.params.schools.splice(foundIndex, 1);
        }
    }


    search(searchText) {
        const data = this.allTeamsSchoolsService.runSearch(_.cloneDeep(this.savedData.county), this.searchPipe['cities'], searchText, this.searchPipe['searchType']);
        this.searchText = searchText;
        this.config.data.county = data;
        this.config.data.count = 0;
        data.forEach(item => this.config.data.count += item.count);
        this.searchModeEnabled.emit({id: this.config.data.statesId, searchText});
    }

    closeSearch() {
        this.searchText = this.config.globalSearchText;
        this.searchModeDisabled.emit({id: this.config.data.statesId});
        this.config.data = _.cloneDeep(this.savedData);
    }

    ngOnInit() {
        this.savedData = this.config.order === 'states' ? _.cloneDeep(this.config.data) : null;
        this.searchText = this.config.globalSearchText;

        if (this.config.isFirstLoadState && this.config.order === 'states' && this.config.firstLoadData.firstLoad) {
            this.config.firstLoadData.firstLoad = false;
            const countyId = this.config.firstLoadData.firstCounty.countyId;
            const sportId = this.config.firstLoadData.firstSport.sportId;
            const school = this.config.firstLoadData.firstSport.schools[0];
            this.toggleState(this.config.data.statesId);
            this.toggleCounty(countyId);
            this.toggleSport(countyId, sportId);
            this.toggleSchool(school);

        }
    }
}