import { RouterModule } from '@angular/router';
import { NgModule } from '@angular/core';
import { MyAccountComponent } from './my-account.component';
import { SharedModule } from '../../+shared/modules/shared.module';
import { CanActivateAuthGuard } from '../../+shared/guards/auth.guard';

@NgModule({
    imports: [
        RouterModule.forChild([
            {
                path: '', canActivateChild: [CanActivateAuthGuard],
                children: [
                    {path: '', component: MyAccountComponent},
                ]
            },
        ]),
        SharedModule
    ],
    declarations: [MyAccountComponent],
    providers: [CanActivateAuthGuard]
})
export class MyAccountModule {
}
