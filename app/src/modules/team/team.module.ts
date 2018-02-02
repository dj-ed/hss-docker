import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { SharedModule } from '../../+shared/modules/shared.module';
import { RosterComponent } from './roster/roster.component';
import { HeadlinesComponent } from './headlines/headlines.component';
import { ScheduleComponent } from './schedule/schedule.component';
import { GalleryComponent } from './gallery/gallery.component';
import { TeamComponent } from './team.component';
import { HomeCourtComponent } from './home-court/home-court.component';
import { TeamService } from './team.service';
import { TeamStatsComponent } from './stats/team-stats.component';
import { ScoreboardComponent } from './stats/scoreboard/scoreboard.component';
import { LeaderboardComponent } from './stats/leaderboard/leaderboard.component';
import { TeamStandingsComponent } from './stats/team-standings/team-standings.component';
import { LiveComponent } from './live/live.component';
import { GameRecapComponent } from './home-court/game-recap/game-recap.component';
import { StatsService } from "../../+shared/services/stats.service";
import { SchoolService } from "../school/school.service";
import { PlayerService } from "../player/player.service";
import { NewsService } from "../../+shared/services/news.service";
import { CoachCornerComponent } from "./coach-corner/coach-corner.component";
import { HeadlinesService } from "../../+shared/services/headlines.service";
import {SearchPipe} from "./+shared/search.pipe";
import {KrossoverComponent} from "./krossover/krossover.component";
import {KrossoverInnerComponent} from "./krossover-inner/krossover-inner.component";

@NgModule({
    imports: [
        RouterModule.forChild([{
            path: ':id', component: TeamComponent, children: [
                {path: '', component: HomeCourtComponent, pathMatch: 'full'},
                {path: 'roster', component: RosterComponent},
                {
                    path: 'stats', component: TeamStatsComponent,
                    children: [
                        {path: '', redirectTo: 'scoreboard', pathMatch: 'full'},
                        {path: 'scoreboard', component: ScoreboardComponent},
                        {path: 'leaderboard', component: LeaderboardComponent},
                        {path: 'team-standings', component: TeamStandingsComponent}
                    ]
                },
                {path: 'headlines', component: HeadlinesComponent},
                {path: 'schedule', component: ScheduleComponent},
                {path: 'gallery', component: GalleryComponent},
                {path: 'live', component: LiveComponent},
                {path: 'krossover', component: KrossoverComponent},
                {path: 'krossover-inner', component: KrossoverInnerComponent},
            ]
        },
        ]),
        SharedModule
    ],
    declarations: [TeamComponent, HomeCourtComponent, GameRecapComponent, RosterComponent, ScoreboardComponent,
        LeaderboardComponent, TeamStandingsComponent, HeadlinesComponent, ScheduleComponent, KrossoverComponent,
        GalleryComponent, TeamStatsComponent, LiveComponent, CoachCornerComponent, SearchPipe, KrossoverInnerComponent],
    providers: [SchoolService, TeamService, StatsService, PlayerService, NewsService, HeadlinesService]
})
export class TeamModule {
}
