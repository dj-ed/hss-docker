import { Injectable } from '@angular/core';
import { AjaxService } from '../../services/ajax.service';
import { RootService } from '../../../modules/root/root.service';
import { SportService } from '../../../modules/sport/sport.service';
import { SchoolService } from '../../../modules/school/school.service';
import { TeamService } from '../../../modules/team/team.service';

@Injectable()
export class UpcomingService {

    constructor(public ajaxService: AjaxService, public rootService: RootService,
                public sportService: SportService,
                public schoolService: SchoolService,
                public teamService: TeamService) {
    }

    getGames(type) {
        const data = {};
        data['genderId'] = this.rootService.currentGenderId;
        data['type'] = type;
        let typeSubscriber;
        if (type === 'sport') {
            typeSubscriber = this.sportService.subscribeSport();
        } else if (type === 'school') {

            typeSubscriber = this.schoolService.subscribeSchool();
        } else if (type === 'team') {
            typeSubscriber = this.teamService.subscribeTeam();
        } else {
            return this.ajaxService.post('game/upcoming', data);
        }

        return typeSubscriber.concatMap((rez) => {
            data['id'] = rez.id;
            return this.ajaxService.post('game/upcoming', data);
        });
    }
}
