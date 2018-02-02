import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { PageNotFoundComponent } from './page-not-found.component';
import { SharedModule } from '../../+shared/modules/shared.module';

@NgModule({
    imports: [
        RouterModule.forChild([
            {path: 'not-found', component: PageNotFoundComponent},
        ]),
        SharedModule
    ],
    declarations: [PageNotFoundComponent],
})
export class PageNotFoundModule {
}
