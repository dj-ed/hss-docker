import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { ReversePipe } from '../pipes/reverse.pipe';
import { CommentsComponent } from '../components/comments/comments.component';
import { OneCommentComponent } from '../components/comments/one-comment/one-comment.component';
import { AudioPlayerComponent } from '../components/comments/audio-player/audio-player.component';
import { ReportAbusePopupComponent } from '../components/comments/report-abuse-popup/report-abuse-popup.component';
import { ScrollLoadDirecrive } from '../directives/scroll-load.direcrive';
import { LastVideoNewsComponent } from '../components/headlines/last-video-news/last-video-news.component';
import { MostPopularComponent } from '../components/headlines/most-popular/most-popular.component';
import { LatestNewsComponent } from '../components/headlines/latest-news/latest-news.component';
import { NewsListStreamComponent } from '../components/headlines/news-list-stream/news-list-stream.component';
import { OneNewsComponent } from '../components/headlines/one-news/one-news.component';
import { HotTagsComponent } from '../components/headlines/hot-tags/hot-tags.component';
import { InternalSearchComponent } from '../components/internal-search/internal-search.component';
import { StreamingNowComponent } from '../components/live/streaming-now/streaming-now.component';
import { StreamingTodayComponent } from '../components/live/streaming-today/streaming-today.component';
import { UpcommingEventsComponent } from '../components/live/upcomming-events/upcomming-events.component';
import { SchoolListNavComponent } from '../components/school-list-nav/school-list-nav.component';
import { ShareComponent } from '../components/share/share.component';
import { SimpleSelectComponent } from '../components/simple-select/simple-select.component';
import { SocialStreamComponent } from '../components/social-stream/social-stream.component';
import { FullBoardComponent } from '../components/stats/full-board/full-board.component';
import { LeaderBoardComponent } from '../components/stats/leader-board/leader-board.component';
import { ScoreBoardComponent } from '../components/stats/score-board/score-board.component';
import { TeamStandingsComponent } from '../components/stats/team-standings/team-standings.component';
import { TopNewsComponent } from '../components/top-news/top-news.component';
import { UpcomingGamesComponent } from '../components/upcoming-games/upcoming-games.component';
import { LikeActionDirective } from "../directives/like-action.directive";
import { SwiperModule } from "ngx-swiper-wrapper";
import { MediaGaleryComponent } from "./gallery/media-gallery/media-gallery.component";
import { ModalDirective } from "../directives/modal.directive";
import { AuthComponent } from "../components/auth/auth.component";
import { SubscActionDirective } from "../directives/subsc-action.directive";
import { SortPipe } from '../../modules/root/root-header/location-pipes/location.pipe';
import { SortNumPipe } from '../../modules/root/root-header/location-pipes/location-number.pipe';
import { SortPipeTeams } from '../../modules/root/root-header/location-pipes/location-teams.pipe';
import { ReactiveFormsModule, FormsModule } from "@angular/forms";
import { VgCoreModule } from 'videogular2/core';
import { VgControlsModule } from 'videogular2/controls';
import { VgOverlayPlayModule } from 'videogular2/overlay-play';
import { VgBufferingModule } from 'videogular2/buffering';
import { ScrollStickerDirective } from "../directives/scroll-sticker.directive";
import { ScrollbarModule } from 'ngx-scrollbar';
import { SortPipeTeamsNames } from '../../modules/root/root-header/location-pipes/location-team-name.pipe';
import { TopButtons } from "../../modules/root/root-header/location-pipes/location-top-button.pipe";
import { ScoreBoardSearchPipe } from "../components/stats/pipes/score-board-search.pipe";
import {LeaderBoardSearchPipe} from "../components/stats/pipes/leader-board-search.pipe";
import {TeamStandingsSearchPipe} from "../components/stats/pipes/team-standings-search.pipe";
import {FullBoardSearchPipe} from "../components/stats/pipes/full-board-search.pipe";

@NgModule({
    declarations: [
        MediaGaleryComponent,
        ReversePipe, ScrollLoadDirecrive, CommentsComponent, OneCommentComponent, AudioPlayerComponent,
        ReportAbusePopupComponent, LastVideoNewsComponent, MostPopularComponent, LatestNewsComponent,
        NewsListStreamComponent, OneNewsComponent, HotTagsComponent, InternalSearchComponent,
        ModalDirective, SortPipe, SortNumPipe, AuthComponent, StreamingNowComponent,
        StreamingTodayComponent, UpcommingEventsComponent, SchoolListNavComponent,
        ShareComponent, SimpleSelectComponent, SocialStreamComponent, FullBoardComponent, LeaderBoardComponent,
        ScoreBoardComponent, TeamStandingsComponent, TopNewsComponent, UpcomingGamesComponent, LikeActionDirective,
        SubscActionDirective, ScrollStickerDirective, SortPipeTeams, SortPipeTeamsNames, ScoreBoardSearchPipe,
        LeaderBoardSearchPipe, TeamStandingsSearchPipe, TopButtons, FullBoardSearchPipe

    ],
    imports: [CommonModule, ReactiveFormsModule, FormsModule, RouterModule.forChild([]),
         SwiperModule, VgCoreModule, VgControlsModule, VgOverlayPlayModule, VgBufferingModule, ScrollbarModule
    ],
    exports: [CommonModule, SwiperModule,ReactiveFormsModule, FormsModule,
        VgCoreModule, VgControlsModule, VgOverlayPlayModule, VgBufferingModule,
        ReversePipe, ScrollLoadDirecrive, MediaGaleryComponent,
        ModalDirective, SortPipe, SortNumPipe, AuthComponent, CommentsComponent, OneCommentComponent, AudioPlayerComponent,
        MostPopularComponent, ReportAbusePopupComponent, LastVideoNewsComponent, LatestNewsComponent,
        NewsListStreamComponent, OneNewsComponent, HotTagsComponent, InternalSearchComponent, StreamingNowComponent,
        StreamingTodayComponent, UpcommingEventsComponent, SchoolListNavComponent,
        ShareComponent, SimpleSelectComponent, SocialStreamComponent, FullBoardComponent, LeaderBoardComponent,
        ScoreBoardComponent, TeamStandingsComponent, TopNewsComponent, UpcomingGamesComponent, LikeActionDirective,
        SubscActionDirective, ScrollStickerDirective, ScrollbarModule, SortPipeTeams, SortPipeTeamsNames, ScoreBoardSearchPipe,
        LeaderBoardSearchPipe, TeamStandingsSearchPipe, TopButtons, FullBoardSearchPipe

    ]
    // Never use Services here!
})
export class PartialComponentsModule {
}
