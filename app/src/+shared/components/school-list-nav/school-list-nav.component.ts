import { Component, Input, OnInit, OnDestroy, ChangeDetectorRef, ChangeDetectionStrategy } from '@angular/core';
import { HeaderService } from '../../../modules/root/root-header/root-header.service';
import { RootService } from '../../../modules/root/root.service';
import { School } from '../../../models/school.model';
import { Observable } from "rxjs/Observable";

@Component({
    selector: 'school-list-nav',
    templateUrl: './school-list-nav.component.html',
    styleUrls: ['./school-list-nav.component.scss'],
    changeDetection: ChangeDetectionStrategy.OnPush
})
export class SchoolListNavComponent implements OnInit, OnDestroy {
    @Input() title: string;
    @Input() className?: string;
    @Input() news;
    @Input() scrollObservable?: Observable<any> = null;
    visible: boolean = false;
    isTitle?: boolean = false;
    currentSchool: School;
    isWork = true;

    constructor(public headerService: HeaderService, public rootService: RootService, public ref: ChangeDetectorRef) {
        if (rootService.isBrowser()) {
            this.ref.detach();
            Observable.interval(300).takeWhile(() => this.isWork)
                .subscribe(() => this.ref.detectChanges());
        }
    }

    ngOnInit(): void {

        if (this.scrollObservable) {
            let data = {direction: '', count: 0};
            const limit = 3;
            const scroll$ = this.scrollObservable.takeWhile(() => this.isWork);
            const bottom$ = scroll$.filter((scroll) => {
                return scroll.current.direction === 'bottom' && !scroll.current.preTitle && (scroll.current.index > 0 || scroll.current.percent > 20);
            });
            const top$ = scroll$.filter((scroll) => scroll.current.direction === 'top' || scroll.current.preTitle);

            scroll$.filter((scroll) => scroll.current.preTitle).subscribe(() => {
                this.isTitle = false;
                data = {direction: '', count: 0};
            });

            bottom$.do(() => {
                if (data.direction !== 'bottom' && data.count > 0) {
                    data = {count: 0, direction: 'bottom'};
                }
            }).map(() => {
                data.count++;
                return data;
            })
                .filter(acc => acc.count >= limit && data.direction === 'bottom')
                .subscribe((scroll) => this.isTitle = true);

            top$.do(() => {
                if (data.direction !== 'top' && data.count > 0) {
                    data = {count: 0, direction: 'top'};
                }
            }).map(() => {
                data.count++;
                return data;
            })
                .filter(acc => acc.count >= limit && data.direction === 'top')
                .subscribe((scroll) => this.isTitle = false);
        }
        this.currentSchool = this.rootService.schoolList[0];
    }

    toggle() {
        this.visible = !this.visible;
    }

    ngOnDestroy() {
        this.isWork = false;
    }
}
