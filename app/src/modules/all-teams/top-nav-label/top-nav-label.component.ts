import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {RootService} from "../../root/root.service";
import { apperaAnimation } from './top-nav-label.animation';
import * as _ from 'lodash';
import {TransformDataPipe} from "../pipes/transform-data.pipe";

@Component({
    selector: 'top-nav-label',
    templateUrl: './top-nav-label.component.html',
    styleUrls: ["../../../styles/all-schools-teams.scss", "./top-nav-label.component.scss"],
    animations: [apperaAnimation],
})
export class TopNavLabel implements OnInit {
    @Input() renderData: {
        order: any,
        alphabetParam: any,
        fullData: any,
        searchText,
        searchParams,
        params
    };

    @Input('config')
    set config(config: { state: any, county: any, sport: any, school: any, char: any, }) {
        if (!config) {
            this.isActive = false;
            return;
        }
        Object.keys(config).forEach(key => {
            if (config[key]) {
                switch (key) {
                    case 'state' :
                        this._config[key] = _.find(this.renderData.fullData.states, {statesId: +config[key].dataset.id});
                        break;
                    case 'county' :
                        this._config[key] = _.find(this.renderData.fullData.cities, {countyId: +config[key].dataset.id});
                        break;
                    case 'sport':
                        this._config[key] = _.find(this.renderData.fullData.sports, {
                            sportId: +config[key].dataset.id,
                            countyId: +config[key].dataset.countyid
                        });
                        break;
                    case 'char':
                        this._config[key] = config[key].dataset;
                        break;
                    case 'school' :
                        this._config[key] = _.find(this.renderData.fullData.schools, {schoolId: +config[key].dataset.id});
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

    constructor(public rootService: RootService, public transformDataPipe: TransformDataPipe) {

    }

    ngOnInit() {
    }
}