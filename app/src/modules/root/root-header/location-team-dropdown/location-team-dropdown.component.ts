import { Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { apperaAnimation } from './location-team-dropdown.animations';
import {ScrollbarComponent} from "ngx-scrollbar";
import {HeaderService} from "../root-header.service";
import {RootService} from "../../root.service";

@Component({
    selector: 'location-team-dropdown',
    templateUrl: './location-team-dropdown.component.html',
    styleUrls: ['./location-team-dropdown.component.scss'],
    animations: [apperaAnimation],
    host: {
        '(document:click)': 'onClick($event)',
    },
})

export class LocationTeamDropdownComponent implements OnInit {
    schools = [];
    @ViewChild('searchWrap') searchWrap: ElementRef;
    @ViewChild(ScrollbarComponent) scrollRef: ScrollbarComponent;
    chosenState: string;
    chosenStateId: string;
    showDrop: boolean = false;
    search_text: string;
    button_filter : any = 'all';

    constructor(private _eref: ElementRef,  public headerService: HeaderService, public rootService: RootService) {
    }

    ngOnInit(): void {
        this.headerService.getLocationTeams().subscribe(res => {
            this.schools = res.data;
        });
    }

    sportImg(sportId){
        switch(sportId) {
            case 1: {
                return 'basketball.svg';
            }
            case 2: {
                return 'volleyball.svg';
            }
            default: {
                return 's_all.svg';
            }
        }
    }

    sportName(sportId){
        switch(sportId) {
            case 1: {
                return 'Basketball - ';
            }
            case 2: {
                return 'Bolleyball - ';
            }
            default: {
                return '';
            }
        }
    }

    locationChosed(state){
        this.showDrop = false;
        this.search_text = '';
        this.chosenState = state.teamName + ', '+ state.genderName + ' ' + state.varsityName;
        this.chosenStateId = state.teamId;
    }

    scroller(e) {
        this.scrollRef.scrollYTo(this.searchWrap.nativeElement.querySelector('.one-group[data-id="' + e.toElement.innerHTML + '"]').offsetTop, 180);
    }

    onClick(event) {
        if (!this._eref.nativeElement.contains(event.target)) {
            this.showDrop = false;
        }
    }
}