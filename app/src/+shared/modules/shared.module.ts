import { NgModule } from '@angular/core';
import { GalleryModule } from './gallery/gallery.module';
import { ScheduleModule } from './schedule/schedule.module';
import { PartialComponentsModule } from './partial-components.module';

@NgModule({
    declarations: [],
    imports: [
        PartialComponentsModule,
        GalleryModule,
        ScheduleModule,
    ],
    exports: [
        PartialComponentsModule,
        GalleryModule,
        ScheduleModule,
    ],
    // Never use Services here!
})
export class SharedModule {
}
