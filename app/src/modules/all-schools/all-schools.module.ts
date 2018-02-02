import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { SharedModule } from '../../+shared/modules/shared.module';
import {AllSchoolsComponent} from "./all-schools.component";
import {AllSchoolsService} from "./all-schools.service";
import { DisplayItemComponent } from "./display-item/display-item.component";
import { AlphabeticUniqueSortPipe } from "./pipes/alphabetic-unique-sort.pipe";
import { SearchPipe } from "./pipes/search.pipe";
import { ItemsByCharPipe } from "./pipes/items-by-char.pipe";
import { AlphabeticSortPipe } from "./pipes/alphabetic-sort.pipe";
import { AllTeamsSchoolsService } from "../../+shared/services/all-teams-schools.service";
import {TopNavLabel} from "./top-nav-label/top-nav-label.component";
import {TransformDataPipe} from "./pipes/transform-data.pipe";

@NgModule({
    imports: [
        RouterModule.forChild([
            {path: ':season', component: AllSchoolsComponent},
        ]),
        SharedModule,
    ],
    declarations: [
        DisplayItemComponent, AllSchoolsComponent, AlphabeticUniqueSortPipe, SearchPipe,
        AlphabeticSortPipe, ItemsByCharPipe, TopNavLabel, TransformDataPipe
    ],
    providers: [AllSchoolsService, AllTeamsSchoolsService,
        AlphabeticUniqueSortPipe, SearchPipe, AlphabeticSortPipe, ItemsByCharPipe
    ]
})
export class AllSchoolsModule {
}
