import {
    Component, EventEmitter, Input, OnInit, Output, ViewChildren,
} from '@angular/core';
import {RootService} from "../../root/root.service";
import {AllSchoolsService} from "../all-schools.service";
import * as _ from 'lodash';
import {SwiperDirective} from "ngx-swiper-wrapper";
import {SearchPipe} from "../pipes/search.pipe";
import {AllTeamsSchoolsService} from "../../../+shared/services/all-teams-schools.service";
import { apperaAnimation } from './display-item.animation';

@Component({
    selector: 'display-item',
    templateUrl: './display-item.component.html',
    styleUrls: ["../../../styles/all-schools-teams.scss", './display-item.component.scss'],
    animations: [apperaAnimation],
})
export class DisplayItemComponent implements OnInit {
    @Input('config')
    set setConfig(config) {
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
        firstLoadData: { firstState: any, firstCounty: any, firstLoad: boolean },
        params?: { states: any[], counties: any[], citiesChars: any[], citiesBehindChars: any[], schoolsChars: any[], firstLoad: boolean, data?: any[] },
        isFirstLoadState: boolean,
        globalSearchText: string
    };
    isReadyRoot = false;
    savedData;
    @Output() scrollToChar: EventEmitter<any> = new EventEmitter();
    @Output() searchModeEnabled: EventEmitter<any> = new EventEmitter();
    @Output() searchModeDisabled: EventEmitter<any> = new EventEmitter();
    @ViewChildren(SwiperDirective) public swiperWrapper;
    searchText;
    l = _;


    constructor(public allSchoolsService: AllSchoolsService, public rootService: RootService, public searchPipe: SearchPipe, public allTeamsSchoolsService: AllTeamsSchoolsService) {
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

    toggleCitiesChar(char, cities) {
        const foundIndex = _.indexOf(this.config.params.citiesChars, char);
        if (foundIndex === -1) { // open
            this.config.params.citiesChars.push(char);
        } else if (foundIndex !== -1) { // close
            this.config.params.citiesChars.splice(foundIndex, 1);
            cities.forEach(county => this.toggleCounty(county.countyId, false, true));
        }
    }

    toggleShoolsChar(char) {
        const foundIndex = _.indexOf(this.config.params.schoolsChars, char);
        if (foundIndex === -1) { // open
            this.config.params.schoolsChars.push(char);
            this.loadSchools(char);
        } else if (foundIndex !== -1) { // close
            this.config.params.schoolsChars.splice(foundIndex, 1);
        }
    }

    toggleCounty(countyId, open?, close?) {
        const arrayParam = this.config.order === 'states' ? this.config.params.counties : this.config.params.citiesBehindChars;
        const foundIndex = _.indexOf(arrayParam, countyId);
        if (foundIndex === -1 && !close) { // open
            arrayParam.push(countyId);
            this.loadSchools(countyId);
        } else if (foundIndex !== -1 && !open || foundIndex !== -1 && close && !open) { // close
            arrayParam.splice(foundIndex, 1);
        }
    }

    loadSchools(dataKey, cb?, activeFilter = this.config.order) {
        this.allSchoolsService.loadSchools(dataKey, activeFilter).subscribe((res) => {
            this.config.params.data[dataKey] = res.data;
            if (cb) {
                cb(res);
            }
        });
    }


    search(searchText) {
        if (!searchText) {
            return;
        }
        if (this.config.order !== 'schools') {
            const reqData = {
                searchText, stateId: this.config.order === 'states' ? this.config.data.stateId : null,
                countyId: this.config.order === 'cities' ? this.config.data.cities.map(county => county.id) : null
            };
            const idSearch = this.config.order === 'states' ? this.config.data.stateId : this.config.order === 'cities' ?
                this.config.data.char : this.config.data.char;
            this.allSchoolsService.searchSchools(reqData).subscribe((res) => {
                let count = 0;
                res.data.forEach((schoolsGroup) => {
                    this.config.fullData.cities.forEach(county => {
                        if (schoolsGroup.countyId === county.countyId) {
                            this.config.params.data[county.countyId] = !this.config.params.data[county.countyId] ? schoolsGroup.schools : this.config.params.data[county.countyId];
                        }
                    });
                });
                switch (this.config.order) {
                    case 'states' :
                        this.config.data.county = this.searchPipe.transform(_.cloneDeep(this.savedData.county), 'cities', searchText, this.config.params.data);
                        this.config.data.county.forEach(county => count += county.count_schools);
                        this.config.data.count_all_schools = count;
                        break;
                    case 'cities' :
                        this.config.data.cities = this.searchPipe.transform(_.cloneDeep(this.savedData.cities), 'cities', searchText, this.config.params.data);
                        this.config.data.cities.forEach(county => count += county.count_schools);
                        this.config.data.count = count;
                        break;
                }
                this.searchText = searchText;
                this.searchModeEnabled.emit({id: idSearch, searchText});
            });
        } else {
            this.loadSchools(this.config.data.char, (res) => {
                const searchedSchools = this.searchPipe.transform(res.data, 'schoolsListSimple', searchText, this.config.params.data);
                this.config.data.count = searchedSchools.length;
                this.searchText = searchText;
                this.searchModeEnabled.emit({id: this.config.data.char, searchText});
            });
        }
    }

    closeSearch() {
        if (!this.searchText) {
            return;
        }
        this.searchText = this.config.globalSearchText;
        this.config.data = _.cloneDeep(this.savedData);
        const idSearch = this.config.order === 'states' ? this.config.data.stateId : this.config.order === 'cities' ? this.config.data.char :
            this.config.data.char;
        this.searchModeDisabled.emit({id: idSearch});
    }

    getSwiper(countyId) {
        return this.swiperWrapper._results.find((directive) => +directive.elementRef.nativeElement.dataset.countyid === countyId);
    }


    transitionToChar(char, countyId, level?) {
        if (this.config.viewType === 'lined') {
            this.scrollToChar.emit({countyid: countyId, char, level})
        } else {
            const swiper = this.getSwiper(countyId);
            if (swiper) {
                this.getSwiper(countyId).elementRef.nativeElement.querySelectorAll('.swiper-slide')
                    .forEach((slide, index) => {
                        if (slide.dataset.char.toLowerCase() === char.toLowerCase()) {
                            swiper.setIndex(index);
                        }
                    });
            } else {
                setTimeout(() => this.transitionToChar(char, countyId), 100);
            }
        }
    }

    ngOnInit() {
        console.log(this.config.data);
        this.savedData = _.cloneDeep(this.config.data);
        this.searchText = this.config.globalSearchText;
        if (this.config.isFirstLoadState && this.config.order === 'states' && this.config.firstLoadData.firstLoad) {
            this.config.firstLoadData.firstLoad = false;
            this.toggleState(this.config.data.stateId);
            this.toggleCounty(this.config.firstLoadData.firstCounty.countyId);
        }
    }
}
