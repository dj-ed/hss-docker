import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { IndexComponent } from './map.component';
import { SharedModule } from '../../+shared/modules/shared.module';

@NgModule({
    imports: [
        RouterModule.forChild([
            {path: ':season', component: IndexComponent}
        ]),
        SharedModule
    ],
    declarations: [IndexComponent],
    providers: []
})
export class MapModule {
}
