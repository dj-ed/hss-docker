import { AfterViewInit, Component, ElementRef, OnInit, ViewChild } from '@angular/core';
import { RootService } from '../../root.service';
import { SearchService } from '../../../../+shared/services/search.service';
import { Observable } from 'rxjs/Observable';
import { NavigationEnd, Router } from '@angular/router';
import * as _ from 'lodash';
import { HeaderService } from '../root-header.service';

@Component({
    selector: 'header-search',
    templateUrl: './header-search.component.html',
    styleUrls: ['./header-search.component.scss'],
})

export class HeaderSearchComponent implements OnInit, AfterViewInit {
    @ViewChild('searchInp') searchInp: ElementRef;
    searchResults = [];

    constructor(public rootService: RootService, public searchService: SearchService, public router: Router, public headerService: HeaderService) {
    }

    ngOnInit(): void {
        this.searchService.tagsList = [];
    }

    goToSearch(route?) {
        this.headerService.toogleSearch();
        if (!route) {
            const section = (this.searchResults.length) ? this.searchResults[0]['name'] : 'school';
            route = ['/search', section, this.rootService.seasonField(), {q: this.searchService.getStrFromTags()}];
        }
        this.router.navigate(route);
    }

    ngAfterViewInit() {
        this.searchInp.nativeElement.focus();
        Observable.fromEvent(this.searchInp.nativeElement, 'input').debounceTime(400)
            .map((e: any) => e.target.value)
            .filter((val: any) => {
                this.searchService.tagsList = [];
                if (val.trim().length >= 2) {
                    return true;
                } else {
                    this.searchResults = [];
                }
            })
            .subscribe((rez: any) => {
                this.searchService.searchText = rez;
                this.searchService.divideByTags();
                if (this.searchService.tagsList.length) {
                    this.searchService.navSearchCounts().subscribe((rez) => {
                        this.searchResults = [];
                        _.forEach(rez, (v, k) => {
                            this.searchResults.push({name: k, count: v});
                        });
                        this.searchResults = _.orderBy(this.searchResults, 'count', 'desc');
                    });
                }

            });
    }

}
