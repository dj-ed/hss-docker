import {Component, OnInit, ViewChild, ViewEncapsulation} from '@angular/core';
import {SeoService} from '../../+shared/services/seo.service';
import {AllSchoolsService} from "./all-schools.service";
import {AlphabeticSortPipe} from "./pipes/alphabetic-sort.pipe";
import {AlphabeticUniqueSortPipe} from "./pipes/alphabetic-unique-sort.pipe";
import {RootService} from "../root/root.service";
import {ItemsByCharPipe} from "./pipes/items-by-char.pipe";
import * as _ from 'lodash';
import {ScrollStickerDirective} from "../../+shared/directives/scroll-sticker.directive";

@Component({
    templateUrl: './all-schools.component.html',
    styleUrls: ['../../styles/all-schools-teams.scss'],
})
export class AllSchoolsComponent implements OnInit {
    @ViewChild(ScrollStickerDirective) scrollSticker: ScrollStickerDirective;
    renderData: { states: any, cities: any, schools: any[] } = {states: [], cities: [], schools: []};
    firstLoadData: { firstState: any, firstCounty: any, firstLoad: boolean } = {
        firstState: null,
        firstCounty: null,
        firstLoad: true
    };
    params: { states: any[], counties: any[], citiesChars: any[], citiesBehindChars: any[], schoolsChars: any[], firstLoad: boolean, data?: any } =
        {states: [], schoolsChars: [], counties: [], firstLoad: true, data: {}, citiesChars: [], citiesBehindChars: []};

    viewType = 'regular';
    alphabetParam = {states: 'a-z', cities: 'a-z', schools: 'a-z'};
    activeFilter: string = 'states';
    isReadyRoot = false;
    work = true;
    labelInfo = null;
    searchText;
    searchParams = {};
    savedFullData: any = {};

    constructor(public seoService: SeoService, public allSchoolsService: AllSchoolsService, public alphabeticSortPipe: AlphabeticSortPipe,
                public alphabeticUniqueSortPipe: AlphabeticUniqueSortPipe, public rootService: RootService, public itemsByChar: ItemsByCharPipe) {
        this.rootService.ready$.filter(ready => ready).subscribe((ready) => this.isReadyRoot = ready);
        if (this.rootService.isBrowser()) {
            if ('scrollRestoration' in history) {
                history.scrollRestoration = 'manual';
            }
        }
    }


    initItems() {
        this.renderData.states.forEach(state => {
            state.county = state.county.map(county => {
                county = Object.assign(county, {
                    stateName: state.stateName,
                    stateShortName: state.stateShortName,
                    stateId: state.stateId
                });
                county.char = county.countyName[0].toLowerCase();
                return county;
            });
            this.renderData.cities = this.renderData.cities.concat(state.county);
        });
    }

    scrollTo(data) {
        if (this.viewType === 'lined' || (data.type == 'charCounty' || data.type === 'schoolsChar')) {
            data.char = data.char.toLowerCase();
            this.scrollSticker.scrollToChar(data);
        }
    }

    loadFullList() {
        this.allSchoolsService.loadList().take(1).subscribe((res: any) => {
            if (!_.isEqual(this.renderData.states, res.data)) {
                this.params = {
                    states: [],
                    schoolsChars: [],
                    counties: [],
                    firstLoad: true,
                    data: this.params.data,
                    citiesChars: [],
                    citiesBehindChars: []
                };
                this.viewType = 'regular';
                this.activeFilter = 'states';
                this.alphabetParam[this.activeFilter] = 'a-z';
                this.searchText = '';
                this.labelInfo = null;
                this.refreshComponentData(res, true);

            }
        });
    }

    loadSchools(dataKey, cb?, activeFilter = this.activeFilter) {
        this.allSchoolsService.loadSchools(dataKey, activeFilter)
            .subscribe((res) => {
                this.params.data[dataKey] = res.data;
                if (cb) {
                    cb();
                }
            });
    }

    refreshComponentData(res = this.savedFullData, isSaveFirst?) {
        /* Refresh component data */
        this.renderData = {states: [], cities: [], schools: []};
        /* Refresh component data */

        /* Select first items */
        if (isSaveFirst) {
            this.savedFullData = _.cloneDeep(res);
        }

        this.renderData.states = res.data;
        this.renderData.schools = res.schoolsAlphabeticalList;
        this.initItems();
        if (!res.data.length) {
            return;
        }
        if (isSaveFirst) {
            this.firstLoadData.firstLoad = true;
            this.firstLoadData.firstState = this.alphabeticSortPipe.transform(this.renderData.states, 'states', this.alphabetParam[this.activeFilter])[0];
            this.firstLoadData.firstCounty = this.alphabeticSortPipe.transform(this.firstLoadData.firstState.county, 'cities', this.alphabetParam[this.activeFilter])[0];

            /* Select first items */

            /* Load First List Schools */
            this.loadSchools(this.firstLoadData.firstCounty.countyId);
            /* Load First List Schools */
        }
    }


    search(searchText) {
        if (!searchText) {
            return;
        }
        this.allSchoolsService.loadSearchList(searchText).subscribe((res) => {
            this.refreshComponentData(res);
            this.searchText = searchText;
        });
    }

    closeSearch() {
        if (!this.searchText) {
            return;
        }
        this.searchText = '';
        this.labelInfo = null;
        this.refreshComponentData();
        if (this.scrollSticker) {
            this.scrollSticker.refreshView();
        }
    }

    openLocalSearch(id, searchText) {
        this.searchParams[id] = searchText;
    }

    closeLocalSearch(id, searchText) {
        delete this.searchParams[id];
    }

    ngOnInit(): void {
        if (this.rootService.isBrowser()) {
            window.scrollTo(0, 0);
        }
        this.seoService
            .setTitle('All Schools')
            .setDescription('All Schools Page');

        this.rootService.seasonChange$.filter(season => typeof season !== 'boolean')
            .takeWhile(() => this.work)
            .subscribe(() => this.loadFullList());
        this.loadFullList()

    }
}
