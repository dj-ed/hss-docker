import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { SharedModule } from '../../+shared/modules/shared.module';
import { InfoComponent } from './info/info.component';
import { ScheduleComponent } from './schedule/schedule.component';
import { HeadlinesComponent } from './headlines/headlines.component';
import { SchoolService } from './school.service';
import { HomeComponent } from './home/home.component';
import { SchoolComponent } from './school.component';
import { LiveComponent } from './live/live.component';
import { SchoolHeaderComponent } from './+shared/school-header.component';
import { SchoolHeaderSocialsComponent } from './+shared/school-header-socials.component';
import { TeamService } from '../team/team.service';
import { PlayerService } from '../player/player.service';
import { NewsService } from "../../+shared/services/news.service";
import { SchoolSportSelectComponent } from "./+shared/school-sport-select.component";
import { HeadlinesService } from "../../+shared/services/headlines.service";

@NgModule({
    imports: [
        RouterModule.forChild([{
            path: ':id', component: SchoolComponent, children: [
                {path: '', component: HomeComponent, pathMatch: 'full'},
                {path: 'info', component: InfoComponent},
                {path: 'schedule', component: ScheduleComponent},
                {path: 'schedule/:sport', component: ScheduleComponent},
                {path: 'live', component: LiveComponent},
                {path: 'headlines', component: HeadlinesComponent},
            ]
        }
        ]),
        SharedModule,
    ],
    declarations: [SchoolComponent, SchoolHeaderComponent, HomeComponent, InfoComponent, ScheduleComponent,
        HeadlinesComponent, LiveComponent, SchoolHeaderSocialsComponent, SchoolSportSelectComponent],
    providers: [SchoolService, TeamService, PlayerService, NewsService, HeadlinesService]
})
export class SchoolModule {
}
