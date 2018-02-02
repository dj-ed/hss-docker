import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { OverviewComponent } from './overview/overview.component';
import { SharedModule } from '../../+shared/modules/shared.module';
import { TopNewsComponent } from './top-news/top-news.component';
import { ScheduleComponent } from './schedule/schedule.component';
import { GalleryComponent } from './gallery/gallery.component';
import { SchoolService } from '../school/school.service';
import { LiveComponent } from './live/live.component';
import { PageScrollService } from 'ng2-page-scroll';
import { PlayerService } from "../player/player.service";
import { NewsService } from "../../+shared/services/news.service";
import { SportScoreboardComponent } from "./overview/sport-scoreboard/sport-scoreboard.component";
import { StatsService } from "../../+shared/services/stats.service";
import { SportLeaderboardComponent } from "./overview/sport-leaderboard/sport-leaderboard.component";
import { SportStandingsComponent } from "./overview/sport-standings/sport-standings.component";
import { HeadlinesService } from "../../+shared/services/headlines.service";
import {HeadlinesComponent} from "./headlines/headlines.component";
import {NewsLineComponent} from "./headlines/news-line/news-line.component";

@NgModule({
    imports: [
        RouterModule.forChild([
            {path: 'overview/:sport/:season', component: OverviewComponent},
            {path: 'overview/:sport/:season/:gender', component: OverviewComponent},
            //
            {path: 'top-news/:sport/:season', component: TopNewsComponent},
            {path: 'top-news/:sport/:season/:gender', component: TopNewsComponent},

            {path: 'schedule/:sport/:season', component: ScheduleComponent},
            {path: 'schedule/:sport/:season/:gender', component: ScheduleComponent},

            {path: 'live/:sport/:season', component: LiveComponent},
            {path: 'live/:sport/:season/:gender', component: LiveComponent},
            {path: 'headlines/:sport/:season', component: HeadlinesComponent},
            {path: 'headlines/:sport/:season/:gender', component: HeadlinesComponent},
            {path: 'gallery/:sport/:season', component: GalleryComponent},
            {path: 'gallery/:sport/:season/:gender', component: GalleryComponent},
        ]),
        SharedModule,
    ],
    declarations: [
         TopNewsComponent,
        NewsLineComponent, HeadlinesComponent,
        ScheduleComponent,LiveComponent,GalleryComponent,OverviewComponent,SportScoreboardComponent, SportLeaderboardComponent, SportStandingsComponent,
    ],
    providers: [
        PageScrollService,
        SchoolService,PlayerService, NewsService, HeadlinesService, StatsService]
})
export class SportModule {
}
