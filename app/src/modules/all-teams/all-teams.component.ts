import {Component, OnDestroy, OnInit, ViewChild} from '@angular/core';
import {SeoService} from '../../+shared/services/seo.service';
import {AllTeamsService} from './all-teams.service';
import {RootService} from "../root/root.service";
import {SportService} from "../sport/sport.service";
import {AlphabeticSortPipe} from "./pipes/alphabetic-sort.pipe";
import {AlphabeticUniqueSchoolsPipe} from "./pipes/alphabetic-unique-schools.pipe";
import {SchoolsByCharPipe} from "./pipes/schools-by-char.pipe";
import {ScrollStickerDirective} from "../../+shared/directives/scroll-sticker.directive";
import {Observable} from "rxjs/Observable";
import * as _ from 'lodash';
import {Subject} from "rxjs/Subject";

@Component({
    templateUrl: './all-teams.component.html',
    styleUrls: ['../../styles/all-schools-teams.scss', './all-teams.component.scss'],
})
export class AllTeamsComponent implements OnInit, OnDestroy {
    @ViewChild(ScrollStickerDirective) scrollSticker: ScrollStickerDirective;
    renderData: { states: any, cities: any, sports: any, schools: any } = {
        states: [], sports: [], cities: [], schools: []
    };
    firstLoadData: { firstState: any, firstCounty: any, firstSport: any, firstSchool: any, firstLoad: boolean }
        = {firstState: null, firstCounty: null, firstSport: null, firstSchool: null, firstLoad: true};
    params: { states: any[], counties: any[], sports: any[], schools: any[], firstLoad: boolean, scrollAnimation?, data?: any[]}
        = {states: [], counties: [], sports: [], schools: [], firstLoad: true, data: [], scrollAnimation: {}};

    viewType = 'regular';
    alphabetParam = {states: 'a-z', cities: 'a-z', schools: 'a-z'};
    activeFilter: string = 'states';
    isReadyRoot = false;
    work = true;
    labelInfo = null;
    searchText;
    searchParams = {};
    changeOrder$: Subject<any> = new Subject();

    constructor(public seoService: SeoService, public allTeamsService: AllTeamsService, public rootService: RootService, public sportService: SportService,
                public alphabeticSortPipe: AlphabeticSortPipe, public alphabeticUniqueSchoolsPipe: AlphabeticUniqueSchoolsPipe,
                public schoolsByCharPipe: SchoolsByCharPipe) {
        this.rootService.ready$.filter(ready => ready).subscribe((ready) => this.isReadyRoot = ready);
        if (this.rootService.isBrowser()) {
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }
        }
    }

    initItems() {
        this.renderData.states.forEach(state => {
            state.county.forEach(county => {
                county = Object.assign(county, {
                    stateName: state.stateName,
                    stateShortName: state.stateShortName,
                    statesId: state.statesId
                });
                county.chars = [];
                county.sports.forEach(sport => {
                    county.chars = county.chars.concat(this.alphabeticUniqueSchoolsPipe.transform(sport.schools));
                    sport.countyId = county.countyId;
                    sport.title = this.rootService.sportById(sport.sportId).title;
                    sport.schools = sport.schools.map((school, index) => {
                        return Object.assign(school, {
                            stateName: state.stateName,
                            stateShortName: state.stateShortName,
                            statesId: state.statesId,
                            countyName: county.countyName,
                            countyShortName: county.countyShortName,
                            sportId: sport.sportId,
                            countyId: county.countyId,
                            sportTitle: sport.title
                        });
                    });
                    county.chars = new Set(county.chars);
                    if (county.chars.has('#')) {
                        county.chars.delete('#');
                        county.chars = Array.from(county.chars);
                        county.chars.push('#');
                    } else {
                        county.chars = Array.from(county.chars);
                    }
                    this.renderData.schools = this.renderData.schools.concat(sport.schools);
                });
                this.renderData.sports = this.renderData.sports.concat(county.sports);
            });
            this.renderData.cities = this.renderData.cities.concat(state.county);
        });
    }

    scrollTo(data) {
        this.scrollSticker.scrollToChar(data, 150, () => {
            // console.log(data);
            this.params.scrollAnimation = data;
            // debugger
        });
    }

    search(searchText) {
        this.searchText = searchText;
    }

    openLocalSearch(id, searchText) {
        this.searchParams[id] = searchText;
    }

    closeLocalSearch(id) {
        delete this.searchParams[id];
    }

    ngOnInit(): void {
        if (this.rootService.isBrowser()) {
            window.scrollTo(0, 0);
        }
        this.seoService
            .setTitle('All Teams')
            .setDescription('All Teams Page');


        Observable.merge(this.rootService.seasonChange$.filter(season => typeof season !== 'boolean'), this.rootService.sportChange$.filter(sport => typeof sport !== 'boolean'))
            .takeWhile(() => this.work)
            .switchMap(() => this.allTeamsService.loadList())
            .subscribe((states: any[]) => {
                if (!_.isEqual(this.renderData.states, states)) {
                    /* Refresh component data */
                    this.renderData = {states: [], sports: [], cities: [], schools: []};
                    this.firstLoadData.firstLoad = true;
                    this.params = {
                        states: [],
                        counties: [],
                        sports: [],
                        schools: [],
                        firstLoad: true,
                        data: this.params.data
                    };
                    this.viewType = 'regular';
                    this.activeFilter = 'states';
                    this.alphabetParam[this.activeFilter] = 'a-z';
                    this.searchText = '';
                    this.labelInfo = null;
                    /* Refresh component data */

                    /* Select first items */
                    this.renderData.states = states;
                    this.initItems();
                    if(!this.renderData.states.length) {
                        return;
                    }
                    this.firstLoadData.firstState = this.alphabeticSortPipe.transform(states, 'states', this.alphabetParam[this.activeFilter], 'local')[0];
                    this.firstLoadData.firstCounty = this.alphabeticSortPipe.transform(this.firstLoadData.firstState.county, 'cities', this.alphabetParam[this.activeFilter], 'local')[0];
                    this.firstLoadData.firstSport = this.firstLoadData.firstCounty.sports[0];
                    this.firstLoadData.firstSchool = this.alphabeticSortPipe.transform(this.firstLoadData.firstSport.schools, 'schools', this.alphabetParam[this.activeFilter], 'local')[0];

                    /* Select first items */

                    /* Load First List Teams */
                    this.allTeamsService.loadSchoolTeams(this.firstLoadData.firstSchool.schoolId)
                        .subscribe((teams) => {
                            this.params.data[this.firstLoadData.firstSchool.schoolId] = teams.data;
                        });
                    /* Load First List Teams */
                }
            });


    }

    ngOnDestroy() {
        this.work = false;
    }
}
