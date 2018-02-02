import { Component, Input, Output, EventEmitter, OnInit, ViewChild } from '@angular/core';
import * as _ from 'lodash';

@Component({
    selector: 'simple-select',
    templateUrl: './simple-select.component.html',
    styleUrls: ['./simple-select.component.scss'],
})

export class SimpleSelectComponent implements OnInit {
    @Input() values: any = [];
    @Input() className: string = '';
    @Input() selectedValue: string;
    @Input() isDisabled: boolean = false;
    @Output() change = new EventEmitter();
    @ViewChild('dropDown') dropDown;

    opened: boolean = false;
    currentClass: string = '';
    currentLabel: string = '';

    ngOnInit() {
        document.querySelector('body').addEventListener('click', (btnEvent: any) => {
            const elem: any = document.elementFromPoint(btnEvent.clientX, btnEvent.clientY);
            if (!elem.closest('.dropdown') || this.dropDown.nativeElement !== elem.closest('.dropdown')) {
                this.opened = false;
            }
        });

        if (this.values) {
            let valueChecked: any;
            if (this.selectedValue !== undefined) {
                valueChecked = _.find(this.values, ['value', this.selectedValue.toString()]);
            } else {
                valueChecked = this.values[0];
                this.selectedValue = valueChecked.value;
            }
            this.currentClass = (valueChecked) ? valueChecked.class : '';
            this.currentLabel = (valueChecked) ? valueChecked.label : '';
        }
    }

    select(item) {
        if (this.selectedValue !== item.value) {
            this.selectedValue = item.value;
            this.currentClass = (item.class) ? item.class : '';

            const valueChecked: any = _.find(this.values, ['value', this.selectedValue.toString()]);
            this.currentLabel = (valueChecked) ? valueChecked.label : '';

            this.change.emit(this.selectedValue);
        }
    }

    showList() {
        if (!this.isDisabled) {
            this.opened = !this.opened;
        }
    }

}
