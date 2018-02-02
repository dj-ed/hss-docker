import {Component, EventEmitter, Input, Output, ViewChild, ViewEncapsulation} from '@angular/core';
import { ModalDirective } from "../../../directives/modal.directive";
import { RootService } from "../../../../modules/root/root.service";
@Component({
    selector: 'gallery-preview',
    templateUrl: './gallery-preview.component.html',
    styleUrls: ['../../../../styles/gallery.scss', '../../../../styles/main.scss']
})
export class GalleryPreviewComponent {

    @Input() config?: { index: number, album: any };
    @Output() openAlbum: EventEmitter<any> = new EventEmitter();

    constructor(public rootService: RootService) {
    }

}