import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { HttpModule } from '@angular/http';
import { SharedModule } from '../../+shared/modules/shared.module';
import { ScoreboardComponent } from './scoreboard/scoreboard.component';
import { LeaderboardComponent } from './leaderboard/leaderboard.component';
import { TeamStandingsComponent } from './team-standings/team-standings.component';
import { SportStatComponent } from './sport-stat.component';
import { PartialComponentsModule } from '../../+shared/modules/partial-components.module';
import { RedirectComponent } from './redirect.component';
import { StatsService } from '../../+shared/services/stats.service';
import { PlayerService } from "../player/player.service";

@NgModule({
    imports: [
        RouterModule.forChild([
            {
                path: '', component: SportStatComponent,
                children: [
                    {path: '', component: RedirectComponent, pathMatch: 'full'},

                    {path: 'scoreboard/:sport/:season', component: ScoreboardComponent},
                    {path: 'scoreboard/:sport/:season/:gender', component: ScoreboardComponent},
                    {path: 'scoreboard/:sport/:season/:gender/:varsity', component: ScoreboardComponent},

                    {path: 'leaderboard/:sport/:season', component: LeaderboardComponent},
                    {path: 'leaderboard/:sport/:season/:gender', component: LeaderboardComponent},
                    {path: 'leaderboard/:sport/:season/:gender/:varsity', component: LeaderboardComponent},

                    {path: 'team-standings/:sport/:season', component: TeamStandingsComponent},
                    {path: 'team-standings/:sport/:season/:gender', component: TeamStandingsComponent},
                    {path: 'team-standings/:sport/:season/:gender/:varsity', component: TeamStandingsComponent},

                ]
            }

        ]),
        SharedModule,
        PartialComponentsModule
    ],
    declarations: [ScoreboardComponent, LeaderboardComponent, TeamStandingsComponent, SportStatComponent, RedirectComponent],
    providers: [StatsService, PlayerService]
})
export class SportStatModule {
}
