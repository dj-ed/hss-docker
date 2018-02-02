import {Injectable} from "@angular/core";
import {RootService} from "../root/root.service";
import {AjaxService} from "../../+shared/services/ajax.service";

@Injectable()
export class AllSchoolsService{

    readyRoot$;
    constructor(public ajaxService: AjaxService, public rootService: RootService) {
        this.readyRoot$ = this.rootService.ready$.filter(ready => ready);
    }

    loadList() {
        return this.readyRoot$.concatMap(() => {
            return this.ajaxService.post('all-schools/full-list', {seasonId: this.rootService.currentSeasonId});
        });
    }

    loadSearchList(searchText) {
        return this.readyRoot$.concatMap(() => {
            return this.ajaxService.post('all-schools/search-schools-global', {seasonId: this.rootService.currentSeasonId, searchText});
        });
    }

    loadSchools(requestData, type) {
        return this.readyRoot$.concatMap(() => {
            if (type !== 'schools') {
                return this.ajaxService.post('all-schools/full-list-schools', {seasonId: this.rootService.currentSeasonId, countyId: requestData});
            }  else {
                return this.ajaxService.post('all-schools/schools-by-char', {seasonId: this.rootService.currentSeasonId, char: requestData});
            }
        });
    }

    searchSchools(requestData) {
        requestData.seasonId = this.rootService.currentSeasonId;
        return this.readyRoot$.concatMap(() => {
            return this.ajaxService.post('all-schools/search-school', requestData)
        })
    }


}