import { Component, OnDestroy, OnInit } from '@angular/core';
import { SeoService } from '../../../+shared/services/seo.service';
import { RootService } from '../../root/root.service';
import { ActivatedRoute } from '@angular/router';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';
import * as _ from 'lodash';
import { SportService } from '../sport.service';
import { TeamShort } from '../../../models/team-short.model';
import { Sport } from '../../../models/sport.model';

@Component({
    templateUrl: './overview.component.html',
    styleUrls: ['../../../styles/prefered-sport.scss']
})
export class OverviewComponent implements OnInit, OnDestroy {
    sport: Sport;
    teams$: BehaviorSubject<TeamShort[]> = new BehaviorSubject(undefined);
    subscriber;

    constructor(public seoService: SeoService, public rootService: RootService, public route: ActivatedRoute,
                public sportService: SportService) {
    }

    ngOnInit(): void {
        this.route.params.subscribe(params => {
            this.sportService.loadSport(params['sport']);
        });

        this.subscriber = this.sportService.subscribeSport()
            .map(sport => {
                this.sport = sport;
                this.seoService
                    .setTitle('Sport ' + sport.title)
                    .setDescription('Sport Page');

                // Load teams
                this.sportService.loadSportTeams(sport.id)
                    .subscribe(response => {
                        this.teams$.next(response.data);
                    });
            })
            .concatMap(() => {
                return this.teams$.filter(isReady => !_.isUndefined(isReady));
            })
            .subscribe(() => {
            });

    }

    ngOnDestroy() {
        this.subscriber.unsubscribe();
    }

}
