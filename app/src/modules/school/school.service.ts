import { Injectable } from '@angular/core';
import { AjaxService } from '../../+shared/services/ajax.service';
import { School } from '../../models/school.model';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import { RootService } from '../root/root.service';
import * as _ from 'lodash';

@Injectable()
export class SchoolService {
    schoolCommon$: BehaviorSubject<School> = new BehaviorSubject(undefined);

    constructor(public ajaxService: AjaxService, public rootService: RootService) {
    }

    loadSchoolCommon(schoolId) {
        this.schoolCommon$.next(undefined);
        this.ajaxService.post('school', {schoolId}).subscribe(response => {
            this.schoolCommon$.next(new School(response));
        });
    }

    subscribeSchool() {
        return this.rootService.ready$
            .filter(isReady => isReady)
            .concatMap(() => {
                return this.schoolCommon$.filter(school => !_.isUndefined(school));
            });
    }

    loadSchoolSports(schoolId) {
        return this.ajaxService.post('school/sports', {schoolId});
    }

    loadSchoolInfo(schoolId) {
        return this.ajaxService.post('school/info', {schoolId});
    }

}
