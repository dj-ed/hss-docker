import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { HomeComponent } from './home.component';
import { SharedModule } from '../../+shared/modules/shared.module';
import { AllSchoolsComponent } from './all-schools/all-schools.component';
import { SchoolService } from '../school/school.service';
import { TeamService } from '../team/team.service';
import { PlayerService } from '../player/player.service';
import {NewsService} from "../../+shared/services/news.service";
@NgModule({
    imports: [
        RouterModule.forChild([
            {path: '', component: HomeComponent, pathMatch: 'full'},
            {path: ':season', component: HomeComponent},
        ]),
        SharedModule,

    ],
    declarations: [HomeComponent, AllSchoolsComponent],
    providers: [SchoolService, TeamService, PlayerService, NewsService]
})
export class HomeModule {
}
