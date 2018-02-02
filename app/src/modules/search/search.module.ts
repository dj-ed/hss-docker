import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { SearchComponent } from './search.component';
import { SchoolComponent } from './school/school.component';
import { SearchService } from '../../+shared/services/search.service';
import { SharedModule } from '../../+shared/modules/shared.module';
import { TeamComponent } from './team/team.component';
import { NgxPaginationModule } from 'ngx-pagination';
import { PlayerComponent } from './player/player.component';
import { CoachComponent } from './coach/coach.component';
import { NewsComponent } from './news/news.component';
import { NewsService } from '../../+shared/services/news.service';
import { PlayerService } from '../player/player.service';
import { SchoolService } from '../school/school.service';
import { TeamService } from '../team/team.service';
import { GameComponent } from './game/game.component';
import { MediaComponent } from './media/media.component';

@NgModule({
    imports: [
        RouterModule.forChild([
            {
                path: '', component: SearchComponent,
                children: [
                    {path: 'school/:season', component: SchoolComponent},
                    {path: 'team/:season', component: TeamComponent},
                    {path: 'player/:season', component: PlayerComponent},
                    {path: 'coach/:season', component: CoachComponent},
                    {path: 'news/:season', component: NewsComponent},
                    {path: 'media/:season', component: MediaComponent},
                    {path: 'game/:season', component: GameComponent},

                ]

            }
        ]),
        SharedModule,
        NgxPaginationModule
    ],
    declarations: [SearchComponent, SchoolComponent, TeamComponent, PlayerComponent, CoachComponent, NewsComponent, GameComponent, MediaComponent],
    providers: [ NewsService, PlayerService, SchoolService, TeamService]
})
export class SearchModule {
}
