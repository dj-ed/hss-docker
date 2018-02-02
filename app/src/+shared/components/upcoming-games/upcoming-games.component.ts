import { Component, OnInit, Input, OnDestroy } from '@angular/core';
import { UpcomingService } from './upcoming-games.service';
import { RootService } from '../../../modules/root/root.service';
import * as _ from 'lodash';
import { Game } from '../../../models/game.model';
import { BehaviorSubject } from 'rxjs/BehaviorSubject';

@Component({
    selector: 'upcoming-games',
    templateUrl: './upcoming-games.component.html',
    styleUrls: ['./upcoming-games.component.scss'],
    providers: [UpcomingService],
})
export class UpcomingGamesComponent implements OnInit, OnDestroy {
    config = {
        freeMode: true
    };
    @Input() wrapClass?: string;
    @Input() type: string;
    filterSport: number;
    games: Game[] = [];
    filteredGames$: BehaviorSubject<Game[]> = new BehaviorSubject([]);
    sportList$: BehaviorSubject<Game[]> = new BehaviorSubject([]);
    ready$: BehaviorSubject<boolean> = new BehaviorSubject(false);
    subscriber;

    constructor(public upcomingService: UpcomingService, public rootService: RootService) {
    }

    ngOnInit() {
        this.loadGames();
    }

    getFilteredGames() {
        return _.filter(this.games, {sportId: this.filterSport});
    }

    changeSport(sportId) {
        if (this.filterSport !== sportId) {
            this.filterSport = sportId;
            this.filteredGames$.next(this.getFilteredGames());

            // Change root sport
            this.rootService.changeSport(sportId);
        }
    }

    loadGames() {
        this.filterSport = (!this.rootService.currentSportId) ? this.rootService.defaultSportId : this.rootService.currentSportId;
        this.subscriber = this.upcomingService.getGames(this.type).subscribe(rez => {
            if (rez.data) {
                const sports = [];
                const sportsExists = [];
                this.games = [];

                _.forEach(rez.data, (v) => {
                    this.games.push(new Game(v));
                    sports.push(v.sportId);
                });

                _.forEach(this.rootService.sportList, item => {
                    if (_.indexOf(sports, item.id) !== -1) {
                        sportsExists.push(item);
                    }
                });

                this.sportList$.next(sportsExists);
                this.filteredGames$.next(this.getFilteredGames());
                this.ready$.next(sportsExists.length !== 0);
            }
        });

    }

    showSportList() {
        return this.sportList$.getValue().length > 1;
    }

    getSportUrl() {
        const sport = _.find(this.rootService.sportList, ['id', this.filterSport]);
        return sport.title.toLowerCase();
    }

    ngOnDestroy() {
        if (this.subscriber) {
            this.subscriber.unsubscribe();
        }
    }

}
