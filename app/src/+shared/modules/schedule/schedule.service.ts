import { Injectable } from '@angular/core';
import { AjaxService } from '../../services/ajax.service';
import { RootService } from '../../../modules/root/root.service';

@Injectable()
export class ScheduleService {
    constructor(public ajaxService: AjaxService, public rootService: RootService) {
    }

    getDayGames(date, type?, typeId?) {
        const data = {
                date,
                sportId: this.rootService.currentSportId,
                genderId: this.rootService.currentGenderId

            };
        if (type === 'school') {
                data['schoolId'] = typeId;
            }
        if (type === 'team') {
                data['teamId'] = typeId;
            }
        return this.ajaxService.post('game/schedule-today', data);
    }

    getCalendarGames(dateRange, calendarType, type?, typeId?, sportId?) {
        const data = {
                startDate: dateRange.startDate,
                endDate: dateRange.endDate,
                calendarType,
                sportId: this.rootService.currentSportId,
                genderId: this.rootService.currentGenderId

            };
        if (type === 'school') {
                data['schoolId'] = typeId;
            }
        if (type === 'team') {
                data['teamId'] = typeId;
            }
        return this.ajaxService.post('game/schedule-calendar', data);
    }

    getFullTimeTable(allGames, type?, typeId?, sportId?) {
        const data = {
                sportId: this.rootService.currentSportId,
                genderId: this.rootService.currentGenderId
            };
        if (allGames) {
                data['allGames'] = 1;
            }
        if (type === 'school') {
                data['schoolId'] = typeId;
            }
        if (type === 'team') {
                data['teamId'] = typeId;
            }
        return this.ajaxService.post('game/schedule-full', data);

    }

}
