import {
    AfterViewInit, ChangeDetectorRef, Component, ContentChild, Input, OnInit,
    ViewChild
} from '@angular/core';
import { CalendarService } from './calendar.service';
import { MonthComponent } from './month.component';
import { RootService } from "../../../../modules/root/root.service";
import { ActivatedRoute } from "@angular/router";

@Component({
    selector: 'calendar-component',
    styleUrls: ['./calendar.component.scss'],
    templateUrl: './calendar.component.html'

})
export class CalendarComponent implements OnInit, AfterViewInit {
    @Input() type: string;
    @ViewChild('child') child;
    @ContentChild(MonthComponent) public month: MonthComponent;
    calendarType: string = 'week';

    constructor(public calendarService: CalendarService, private cd: ChangeDetectorRef, public rootService: RootService, public route: ActivatedRoute) {
    }

    ngOnInit() {
        this.calendarService.moduleType = this.type;
    }

    change(changeType) {
        switch (this.calendarType) {
            case 'week':
                this.child.change(changeType);
                break;
            case 'month':
                this.child.change(changeType);
                break;
            case 'year':
                this.child.change(changeType);
                break;
        }
        this.calendarService.loadGames();
    }

    ngAfterViewInit() {
        this.cd.detectChanges();
    }

    changeFilter(type) {
        this.calendarService.calendarType = type;
        this.cd.detectChanges();
    }
}
