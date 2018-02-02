import { Component, ElementRef, Input, OnInit, ViewChild } from '@angular/core';
import { apperaAnimation } from './location-dropdown.animations';
import { ScrollbarComponent } from "ngx-scrollbar";
import { HeaderService } from "../root-header.service";
import {RootService} from "../../root.service";

@Component({
    selector: 'location-dropdown',
    templateUrl: './location-dropdown.component.html',
    styleUrls: ['./location-dropdown.component.scss'],
    animations: [apperaAnimation],
    host: {
        '(document:click)': 'onClick($event)',
    },
})

export class LocationDropdownComponent implements OnInit {
    states = [];
    @ViewChild('searchWrap') searchWrap: ElementRef;
    @ViewChild(ScrollbarComponent) scrollRef: ScrollbarComponent;
    chosenState: string;
    chosenStateId: string;
    showDrop: boolean = false;
    search_text: string;
    @Input() type;

    constructor(private _eref: ElementRef, public headerService: HeaderService, public rootService: RootService) {
    }

    ngOnInit(): void {
        switch(this.type) {
            case 'state': {
                this.headerService.getLocationState().subscribe(res => {
                    this.states = res.data;
                });
                break;
            }
            case 'city': {
                this.headerService.getLocationCity().subscribe(res => {
                    this.states = res.data;
                });
                break;
            }
            default: {
                break;
            }
        }
    }

    locationChosed(state){
        if (state.abbr) {
            this.chosenState = state.name + ', ' + state.abbr;
        }else{
            this.chosenState = state.name
        }
        this.showDrop = false;
        this.search_text = '';
        this.chosenStateId = state.id;
    }

    scroller(e) {
        this.scrollRef.scrollYTo(this.searchWrap.nativeElement.querySelector('.one[data-id="' + e.toElement.innerHTML + '"]').offsetTop, 180);
    }

    onClick(event) {
        if (!this._eref.nativeElement.contains(event.target)) {
            this.showDrop = false;
        }
    }
}
