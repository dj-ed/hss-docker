import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {RootService} from "../../root/root.service";
import { apperaAnimation } from './top-nav-label.animation';
import * as _ from 'lodash';

@Component({
    selector: 'top-nav-label',
    templateUrl: './top-nav-label.component.html',
    styleUrls: ["../../../styles/all-schools-teams.scss", "./top-nav-label.component.scss"],
    animations: [apperaAnimation],
})
export class TopNavLabel implements OnInit {
    @Input() renderData: {
        order: any,
        viewType: string,
        alphabetParam: any,
        fullData: any,
        searchText,
        params,
        searchMode,
        searchParams
    };

    @Input('config')
    set config(config: { state: any, county: any, sport: any, school: any, char: any, }) {
        if (!config) {
            this.isActive = false;
            return;
        }
        Object.keys(config).forEach(key => {
            if(config[key]) {
                switch (key) {
                    case 'state' :
                        this._config[key] = _.find(this.renderData.fullData.states, {stateId: +config[key].dataset.id});
                        break;
                    case 'county' :
                        this._config[key] = _.find(this.renderData.fullData.cities, {countyId: +config[key].dataset.id});
                        break;
                    case 'schoolsChar':
                        this._config[key] = config[key].dataset;
                        break;
                    case 'charCounty':
                        this._config[key] = config[key].dataset;
                }
            } else {
                this._config[key] = null;
            }
        });
        this.isActive = Object.keys(this._config).some((key) => this._config[key]);
    }

    @Output() scrollToChar: EventEmitter<any> = new EventEmitter();
    _config: any = {};
    isActive = false;

    constructor(public rootService: RootService) {

    }

    ngOnInit() {
    }
}