import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { HttpModule } from '@angular/http';
import { SharedModule } from '../../+shared/modules/shared.module';
import { GalleryComponent } from './gallery/gallery.component';
import { BlogComponent } from './blog/blog.component';
import { PlayerComponent } from './player.component';
import { AboutComponent } from './about/about.component';
import { PlayerService } from './player.service';
import { PlayerStatsComponent } from './stats/player-stats.component';
import { TeamScoreboardComponent } from './stats/team-scoreboard/team-scoreboard.component';
import { PlayerStandingsComponent } from './stats/player-standings/player-standings.component';
import { TeamStandingsComponent } from './stats/team-standings/team-standings.component';
import { SchoolService } from "../school/school.service";
import { TeamService } from "../team/team.service";
import { NewsService } from "../../+shared/services/news.service";
import { StatsService } from "../../+shared/services/stats.service";
import { HeadlinesService } from "../../+shared/services/headlines.service";
import {PlayerStandingsSearchPipe} from "./stats/player-standings-search.pipe";

@NgModule({
    imports: [
        RouterModule.forChild([{
            path: ':id', component: PlayerComponent,
            children: [
                {path: '', component: AboutComponent, pathMatch: 'full'},
                {
                    path: 'stats', component: PlayerStatsComponent,
                    children: [
                        {path: '', redirectTo: 'team-scoreboard', pathMatch: 'full'},
                        {path: 'team-scoreboard', component: TeamScoreboardComponent},
                        {path: 'player-standings', component: PlayerStandingsComponent},
                        {path: 'team-standings', component: TeamStandingsComponent},
                    ]
                },
                {path: 'blog', component: BlogComponent},
                {path: 'gallery', component: GalleryComponent},
            ]
        },
        ]),
        SharedModule
    ],
    declarations: [PlayerComponent, AboutComponent, TeamScoreboardComponent, PlayerStandingsComponent,
        BlogComponent, TeamStandingsComponent, GalleryComponent, PlayerStatsComponent, PlayerStandingsSearchPipe],
    providers: [PlayerService, SchoolService, TeamService, NewsService, HeadlinesService, StatsService]
})
export class PlayerModule {
}
