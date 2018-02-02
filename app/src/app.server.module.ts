import { NgModule } from '@angular/core';
import { ServerModule, ServerTransferStateModule } from '@angular/platform-server';
import { ModuleMapLoaderModule } from '@nguniversal/module-map-ngfactory-loader';

import { RootModule } from './modules/root/root.module';
import { RootComponent } from './modules/root/root.component';
import { AppStorage } from "./forStorage/universal.inject";
import { UniversalStorage } from "./forStorage/server.storage";

@NgModule({
    imports: [
        // The AppServerModule should import your AppModule followed
        // by the ServerModule from @angular/platform-server.
        RootModule,
        ServerModule,
        ModuleMapLoaderModule,
        ServerTransferStateModule,


    ],
    // Since the bootstrapped component is not inherited from your
    // imported AppModule, it needs to be repeated here.
    bootstrap: [RootComponent],
    providers: [
        {
            provide: AppStorage, useClass: UniversalStorage
        }
    ],
})
export class AppServerModule {
}
