import { Component, EventEmitter, OnInit, Output, ViewChild } from '@angular/core';
import { changeStateTrigger, clearBtnStateTrigger, searchIconStateTrigger } from './animations';

@Component({
    selector: 'internal-search',
    templateUrl: './internal-search.component.html',
    styleUrls: ['./internal-search.component.scss'],
    animations: [
        changeStateTrigger, clearBtnStateTrigger, searchIconStateTrigger
    ]
})

export class InternalSearchComponent implements OnInit {
    active: boolean;
    text: string = '';
    searched: boolean;
    searchBtnState: string = 'hidden';
    clearBtnState: string = 'hidden';
    searchIconState: string = 'glass';

    @ViewChild('searchText') searchText;

    @Output() search = new EventEmitter();
    @Output() closeSearch = new EventEmitter();

    ngOnInit() {
        this.searchText = this.searchText.nativeElement;
    }

    writeText(event) {
        this.text = event.target.value.trim();
    }

    change(event) {
        this.writeText(event);
        this.searched = false;
        this.searchIconState = 'glass';
        this.clearBtnState = 'visible';
        if (event.keyCode === 13 ) {
            this.searchAction();
        }
    }

    inputFocus() {
        this.searched = false;
        this.searchIconState = 'glass';
        this.clearBtnState = 'visible';
    }

    inputBlur(event) {
        this.writeText(event);
        if ( event.target.value.length === 0 ) {
            this.active = false;
            this.searched = false;
            this.searchIconState = 'glass';
            this.searchBtnState = 'hidden';
            this.clearBtnState = 'visible';
        }
    }

    clearInputAction() {
        this.text = '';
        this.searchText.focus();
    }


    searchAction() {
        if ( this.text == '' || this.text == 'undefined') {
            this.closeSearch.emit();
        }
        if (this.searched) {
            this.closeSearch.emit();
        }
        if (this.active ) {
            if ( !this.searched) {
                this.search.emit(this.text);
            }
            if ( this.searched ) {
                this.text = '';
                this.active = false;
                this.searched = false;
                this.searchIconState = 'glass';
                this.clearBtnState = 'visible';
                this.searchBtnState = 'hidden';
            }else {
                this.searched = true;
                this.searchIconState = 'cross';
                this.clearBtnState = 'hidden';
            }
        } else {
            this.active = true;
            this.searchIconState = 'glass';
            this.clearBtnState = 'hidden';
            this.searchBtnState = 'visible';
            setTimeout(() => {
                this.searchText.focus();
            }, 700);
        }
    }
}
