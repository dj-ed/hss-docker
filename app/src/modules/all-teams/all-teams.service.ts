import {Injectable} from "@angular/core";
import {RootService} from "../root/root.service";
import {AjaxService} from "../../+shared/services/ajax.service";

@Injectable()
export class AllTeamsService {
    readyRoot$;
    constructor(public rootService: RootService, public ajaxService: AjaxService) {
        this.readyRoot$ = this.rootService.ready$.filter(ready => ready);
    }

    loadList() {
        return this.readyRoot$.concatMap(() => {
           return this.ajaxService.post('all-teams/full-list', {seasonId: this.rootService.currentSeasonId, sportId: this.rootService.currentSportId});
        });
    }

    loadSchoolTeams(schoolId) {
        return this.readyRoot$.concatMap(() => {
            return this.ajaxService.post('all-teams/full-list-teams', {seasonId: this.rootService.currentSeasonId, sportId: this.rootService.currentSportId, schoolId});
        });
    }
}