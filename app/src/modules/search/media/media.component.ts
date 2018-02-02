import {Component, Inject, OnInit, ViewChild} from '@angular/core';
import {SearchService} from '../../../+shared/services/search.service';
import {ActivatedRoute, Params} from '@angular/router';
import {SearchComponent} from '../search.component';
import {RootService} from '../../root/root.service';
import {ModalDirective} from "../../../+shared/directives/modal.directive";

@Component({
    templateUrl: './media.component.html',
    styleUrls: ['../../../styles/gallery.scss', '../search.component.scss'],
})
export class MediaComponent implements OnInit {
    @ViewChild(ModalDirective) modal;
    public modalConfig;
    public typeOpenedModal;

    constructor(public rootService: RootService, private route: ActivatedRoute, public searchService: SearchService, @Inject(SearchComponent) private parent: SearchComponent) {
        this.searchService.searchType = 'media';
        this.searchService.currentPage = 1;

        this.route.params.subscribe((params: Params) => {
            this.searchService.tagsList = [];
            this.searchService.searchText = params['q'];
            this.searchService.divideByTags();
            this.searchService.search();
        });
    }

    openModal(type, currentItem) {
        this.typeOpenedModal = type;
        // debugger
        if (this.searchService.searchResults[type].pagination.total !== this.searchService.searchResults[type].data.length) {
            this.searchService.getMediaResults(type, 2)
                .subscribe(() => {
                    this.initConfig(type, currentItem);
                    this.modal.open();
                });
        } else {
           // debugger
            this.initConfig(type, currentItem);
            this.modal.open();
        }
    }

    initConfig(type, currentItem?) {
        this.modalConfig = {modalDirective: this.modal, viewType: 'modal', isLoadingMoreSlide: false};
        if (type !== 'album') {
            this.modalConfig = Object.assign(this.modalConfig, {
                liveLoad: 'media',
                mediaList: this.searchService.searchResults[type].data,
                currentMedia: currentItem
            });
        } else {
            this.modalConfig = Object.assign(this.modalConfig, {
                liveLoad: 'album',
                id: currentItem ? currentItem.id : this.searchService.searchResults[type].data[0].id,
                albumsList: this.searchService.searchResults[type].data,
            });
        }
    }


    loadSearchResult() {
        if (this.searchService.searchResults[this.typeOpenedModal].pagination.total !== this.searchService.searchResults[this.typeOpenedModal].data.length) {
            this.searchService.getMediaResults(this.typeOpenedModal, this.searchService.searchResults[this.typeOpenedModal].pagination.current_page)
                .subscribe((res) => {
                    this.initConfig(this.typeOpenedModal);
                    this.modalConfig.isLoadingMoreSlides = false;
                });
        }
    }


    ngOnInit() {
    }

}
