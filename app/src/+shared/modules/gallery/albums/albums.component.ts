import { Component, Input, OnDestroy, OnInit, ViewChild, ViewEncapsulation } from '@angular/core';
import { GalleryService } from "../gallery.service";
import { ActivatedRoute } from "@angular/router";
import { RootService } from "../../../../modules/root/root.service";
import { MediaGaleryComponent } from "../media-gallery/media-gallery.component";
import { InternalSearchComponent } from "../../../components/internal-search/internal-search.component";
import { SubscActionDirective } from "../../../directives/subsc-action.directive";
import { Album } from '../../../../models/album.model';
import {ModalDirective} from "../../../directives/modal.directive";

@Component({
    selector: 'albums-component',
    templateUrl: './albums.component.html',
    styleUrls: ['../../../../styles/gallery.scss', '../../../../styles/main.scss'],
})
export class AlbumsComponent implements OnInit, OnDestroy {

    @Input() type;
    @ViewChild('mediaGallery') mediaGallery: MediaGaleryComponent;
    @ViewChild('seach') searchRef: InternalSearchComponent;
    @ViewChild(SubscActionDirective) globalScrapbook: SubscActionDirective;
    @ViewChild(ModalDirective) modal: ModalDirective;
    albums: {
        data: Album[];
    };
    page = 1;
    paginationData;
    viewType = 'box';
    searchText = '';
    isWork = true;
    loading;
    gridState: boolean = true;
    currentModalId;


    constructor(public galleryService: GalleryService, public rootService: RootService, public route: ActivatedRoute) {
    }


    loadAlbums() {
        this.loading = true;
        this.galleryService.loadAlbums({type: this.type, page: this.page})
            .takeWhile(() => this.isWork)
            .subscribe((albums) => {
                this.searchText = '';
                if (this.globalScrapbook) {
                    this.globalScrapbook.isActiveSelect = false;
                    this.globalScrapbook.pushAllFavorites.emit([]);
                }
                this.loading = false;
                albums.data = albums.data.map((album) => {
                    return new Album(album);
                });
                if (this.albums) {
                    this.albums.data = this.albums.data.concat(albums.data);
                } else {
                    this.albums = albums;
                    this.paginationData = {total: albums.total, per_page: albums.per_page, last_page: albums.last_page};
                }
                if (this.searchText) {
                    this.searchRef.clearInputAction();
                    this.searchText = null;
                }
                this.page = Math.ceil(this.albums.data.length / albums.per_page) + 1;
                this.albums.data = this.albums.data.map((album, i) => {
                    album.index = String(i);
                    return album;
                });
            });

    }


    openAlbum(albumId) {
        this.currentModalId = albumId;
        this.modal.open();
    }

    ngOnInit() {
        this.loadAlbums();
    }

    ngOnDestroy() {
        this.isWork = false;
    }

    grider() {
        this.gridState = false;
        setTimeout(() => {
            this.gridState = true;
        }, 100);
    }

}
