import { Component, OnDestroy, OnInit, ViewEncapsulation } from '@angular/core';
import { ActivatedRoute, Params, Router } from '@angular/router';
import { SportService } from '../sport/sport.service';
import * as _ from 'lodash';
import { SearchService } from '../../+shared/services/search.service';
import { RootService } from '../root/root.service';
import { HeaderService } from '../root/root-header/root-header.service';

@Component({
    templateUrl: './search.component.html',
    encapsulation: ViewEncapsulation.None,
    styleUrls: ['./search.component.scss'],

})
export class SearchComponent implements OnInit {

    constructor(public router: Router, public sportService: SportService, public searchService: SearchService, public rootService: RootService) {

    }

    ngOnInit(): void {
    }


    removeTag(id) {
        this.searchService.tagsList = _.filter(this.searchService.tagsList, v => {
            return v.id !== id;
        });
        this.goToSearch();
    }

    goToSearch() {
        let tagsStr = '';
        if (this.searchService.getStrFromTags().length) {
            tagsStr = this.searchService.getStrFromTags() + ' ' + this.searchService.searchText;
        } else {
            tagsStr = this.searchService.searchText;
        }
        this.router.navigate(['/search', this.searchService.searchType, this.rootService.seasonField(), {q: tagsStr}]);
    }

}
