import { Component, Inject, OnInit } from '@angular/core';
import { SearchService } from '../../../+shared/services/search.service';
import { ActivatedRoute, Params } from '@angular/router';
import { SearchComponent } from '../search.component';

@Component({
    templateUrl: './team.component.html',
})
export class TeamComponent implements OnInit {

    constructor(private route: ActivatedRoute, public searchService: SearchService, @Inject(SearchComponent) private parent: SearchComponent) {
        this.searchService.searchType = 'team';
        this.searchService.currentPage = 1;

        this.route.params.subscribe((params: Params) => {
            this.searchService.tagsList = [];
            this.searchService.searchText = params['q'];
            this.searchService.divideByTags();
            this.searchService.search();
        });
    }

    ngOnInit() {
    }

}
