import { BrowserModule, BrowserTransferStateModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { RootComponent } from './root.component';
import { HttpClientModule } from "@angular/common/http";
import { AjaxService } from "../../+shared/services/ajax.service";
import { PageNotFoundModule } from "../page-not-found/page-not-found.module";
import { SeoService } from "../../+shared/services/seo.service";
import { RootService } from "./root.service";
import { UserService } from "../../+shared/services/user.service";
import { RootHeaderComponent } from "./root-header/root-header.component";
import { SharedModule } from "../../+shared/modules/shared.module";
import { HeaderService } from "./root-header/root-header.service";
import { TopNewsService } from "../../+shared/services/top-news.service";
import { SportService } from "../sport/sport.service";
import { CommentService } from "../../+shared/components/comments/comments.service";
import { AudioRecordingService } from "../../+shared/components/comments/audio-recording.service";
import { LocationDropdownComponent } from "./root-header/location-dropdown/location-dropdown.component";
import { LocationTeamDropdownComponent } from "./root-header/location-team-dropdown/location-team-dropdown.component";
import { BrowserAnimationsModule } from "@angular/platform-browser/animations";
import { AppCookieService } from "../../+shared/services/app-cookie.service";
import { CookieService } from "ngx-cookie-service";
import { LocationZipComponent } from "./root-header/location-zip/location-zip.component";
import { TextPageComponent } from "../home/text-page/text-page.component";
import { HeaderSearchComponent } from './root-header/header-search/header-search.component';
import { SearchService } from '../../+shared/services/search.service';

export function getRequest(): any {
    // the Request object only lives on the server
    const result = {headers: {cookie: document.cookie}};

    return result;
}
@NgModule({
    bootstrap: [RootComponent],
    imports: [
        HttpClientModule,
        BrowserModule.withServerTransition({appId: 'my-app'}),
        BrowserTransferStateModule,
        BrowserAnimationsModule,


        RouterModule.forRoot([
                {path: '', loadChildren: '../home/home.module#HomeModule', pathMatch: 'full'},
                {path: 'about-us', component: TextPageComponent},
                {path: 'terms-of-use', component: TextPageComponent},
                {path: 'contact-us', component: TextPageComponent},
                {path: 'season', loadChildren: '../home/home.module#HomeModule'},
                {path: 'school', loadChildren: '../school/school.module#SchoolModule'},
                {path: 'team', loadChildren: '../team/team.module#TeamModule'},
                {path: 'all-schools', loadChildren: '../all-schools/all-schools.module#AllSchoolsModule'},
                {path: 'all-teams', loadChildren: '../all-teams/all-teams.module#AllTeamsModule'},
                {path: 'player', loadChildren: '../player/player.module#PlayerModule'},
                {path: 'search', loadChildren: '../search/search.module#SearchModule'},
                {path: 'map', loadChildren: '../map/map.module#MapModule'},
                {path: 'sport-stat', loadChildren: '../sport-stat/sport-stat.module#SportStatModule'},
                {path: 'sport', loadChildren: '../sport/sport.module#SportModule'},
                {path: 'my-account', loadChildren: '../my-account/my-account.module#MyAccountModule'},
                {path: '**', redirectTo: 'not-found'},

            ],
            {initialNavigation: 'enabled'}
        ),

        // Not Found Module
        PageNotFoundModule,
        SharedModule,
    ],

    declarations: [RootComponent, RootHeaderComponent, LocationDropdownComponent, LocationTeamDropdownComponent, TextPageComponent, LocationZipComponent, HeaderSearchComponent],
    providers: [RootService, SeoService, AjaxService, TopNewsService, CommentService, SearchService, AudioRecordingService, UserService, SportService, AppCookieService, CookieService, HeaderService],
})
export class RootModule {
}
