import { BrowserTransferStateModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { REQUEST } from '@nguniversal/express-engine/tokens';
import { AppStorage } from "./forStorage/universal.inject";
import { CookieStorage } from "./forStorage/browser.storage";
import { RootComponent } from "./modules/root/root.component";
import { RootModule } from "./modules/root/root.module";
// import { ServiceWorkerModule } from '@angular/service-worker';

export function getRequest(): any {
    // the Request object only lives on the server
    const result = { headers: { cookie: document.cookie } };

    return result;
}

@NgModule({
    bootstrap: [ RootComponent ],
    imports: [
        BrowserModule.withServerTransition({
            appId: 'my-app'
        }),
        BrowserTransferStateModule,
        // ServiceWorkerModule.register('/ngsw-worker.js'),
        RootModule,
    ],
    providers: [
        {
            // The server provides these in main.server
            provide: REQUEST,
            useFactory: (getRequest)
        },
        { provide: AppStorage, useClass: CookieStorage }
    ]
})
export class BrowserAppModule {}
