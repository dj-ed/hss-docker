import { NgModule } from '@angular/core';
import { CalendarComponent } from './calendar/calendar.component';
import { FullTimetableComponent } from './full-timetable/full-timetable.component';
import { TodayComponent } from './tooday/today.component';
import { PartialComponentsModule } from '../partial-components.module';
import { ScheduleService } from './schedule.service';
import { WeekComponent } from './calendar/week.component';
import { MonthComponent } from './calendar/month.component';
import { YearComponent } from './calendar/year.component';
import { MomentModule } from 'angular2-moment';

import { CalendarService } from './calendar/calendar.service';
import { RouterModule } from '@angular/router';
import { TeamService } from '../../../modules/team/team.service';

@NgModule({
    imports: [
        PartialComponentsModule, RouterModule.forChild([],),MomentModule
    ],
    declarations: [CalendarComponent, FullTimetableComponent, TodayComponent, WeekComponent, MonthComponent, YearComponent, ],
    exports: [CalendarComponent, FullTimetableComponent, TodayComponent, WeekComponent, MonthComponent, YearComponent],
    providers: [ScheduleService, CalendarService, TeamService]
})
export class ScheduleModule {
}
