import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import {AllTeamsComponent} from './all-teams.component';
import { SharedModule } from '../../+shared/modules/shared.module';
import {AllTeamsService} from "./all-teams.service";
import {DisplayItemComponent} from "./display-item/display-item.component";
import {AlphabeticSortPipe} from "./pipes/alphabetic-sort.pipe";
import {AlphabeticUniqueSchoolsPipe} from './pipes/alphabetic-unique-schools.pipe'
import {TopNavLabel} from "./top-nav-label/top-nav-label.component";
import {SchoolsByCharPipe} from "./pipes/schools-by-char.pipe";
import {SearchPipe} from "./pipes/search.pipe";
import {AllTeamsSchoolsService} from "../../+shared/services/all-teams-schools.service";
import {TransformDataPipe} from "./pipes/transform-data.pipe";
import {GetSelectedSportPipe} from "./pipes/getSelectedSport.pipe";



@NgModule({
    imports: [
        RouterModule.forChild([
            {path: ':season', component: AllTeamsComponent},
        ]),
        SharedModule
    ],
    declarations: [AllTeamsComponent, DisplayItemComponent, AlphabeticSortPipe, TopNavLabel, AlphabeticUniqueSchoolsPipe, SchoolsByCharPipe,
        SearchPipe, TransformDataPipe, GetSelectedSportPipe],
    providers: [AllTeamsService, AllTeamsSchoolsService, AlphabeticSortPipe, SearchPipe, AlphabeticUniqueSchoolsPipe, SchoolsByCharPipe, TransformDataPipe]
})
export class AllTeamsModule {
}
