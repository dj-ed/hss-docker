import { Component, Input } from '@angular/core';
import { School } from '../../../models/school.model';

@Component({
    selector: 'school-header-socials',
    templateUrl: './school-header-socials.component.html',
    styleUrls: ['./school-header-socials.component.scss']
})
export class SchoolHeaderSocialsComponent {
    @Input() school: School;

}
