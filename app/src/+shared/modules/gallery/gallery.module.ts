import { NgModule } from '@angular/core';
import { AlbumsComponent } from './albums/albums.component';
import { PartialComponentsModule } from '../partial-components.module';
import {GalleryService} from "./gallery.service";
import {RouterModule} from "@angular/router";
// import {VgCoreModule} from "videogular2/core";
// import {VgControlsModule} from "videogular2/controls";
// import {VgOverlayPlayModule} from "videogular2/overlay-play";
// import {VgBufferingModule} from "videogular2/buffering";
import {GalleryPreviewComponent} from "./gallery-preview/gallery-preview.component";
import {SearchAlbums} from "./search-albums.pipe";
import {DatePipe} from "@angular/common";


@NgModule({
    imports: [
        PartialComponentsModule, RouterModule,
        // VgCoreModule,
        // VgControlsModule, VgOverlayPlayModule, VgBufferingModule
    ],
    declarations: [AlbumsComponent, GalleryPreviewComponent, SearchAlbums],
    exports: [AlbumsComponent, GalleryPreviewComponent
        // VgCoreModule,
        // VgControlsModule, VgOverlayPlayModule, VgBufferingModule
    ],
    providers: [GalleryService, DatePipe]
})
export class GalleryModule {
}
