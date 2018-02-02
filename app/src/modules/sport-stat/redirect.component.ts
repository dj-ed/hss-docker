import { Component, OnInit } from '@angular/core';
import { RootService } from '../root/root.service';
import { Router } from '@angular/router';

@Component({
    template: ' '
})
export class RedirectComponent implements OnInit {

    constructor(public rootService: RootService, public router: Router) {
    }

    ngOnInit(): void {
        let params;
        if (this.rootService.currentGenderId) {
            params = this.rootService.currentVarsityId;
        }
        if (!this.rootService.currentSportId) {
            this.rootService.changeSport(this.rootService.defaultSportId);
        }
        this.router.navigate(this.rootService.sportUrl('/sport-stat/scoreboard', params));
    }
}
