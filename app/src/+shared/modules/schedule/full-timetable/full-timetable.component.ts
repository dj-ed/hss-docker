import {Component, Input, OnDestroy, OnInit} from '@angular/core';
import {Game} from '../../../../models/game.model';
import {SchoolService} from '../../../../modules/school/school.service';
import {ScheduleService} from '../schedule.service';
import * as  _ from 'lodash';
import {DatePipe} from "@angular/common";
import {TeamService} from "../../../../modules/team/team.service";
import {ActivatedRoute} from "@angular/router";
import {RootService} from "../../../../modules/root/root.service";
import {UserService} from "../../../services/user.service";

@Component({
    selector: 'full-timetable',
    templateUrl: './full-timetable.component.html',
    styleUrls: ['../../../components/live/upcomming-events/upcomming-events.component.scss'],
    providers: [DatePipe],

})

export class FullTimetableComponent implements OnInit, OnDestroy {
    games: Game[];
    dividedGames = [];
    typeId: number;
    allGames: boolean;
    remainder: number;
    searchActive: boolean;
    sportSubsriber$;
    typeSubsriber$;
    @Input() type: string;
    @Input() sportId;
    isSelectMode;

    constructor(private schoolService: SchoolService, private teamService: TeamService, private scheduleService: ScheduleService, private datePipe: DatePipe, public route: ActivatedRoute,
                public rootService: RootService, public userService: UserService) {
    }

    ngOnInit() {
        // load games on init component
        if (this.type) {
            this.typeSubsriber$ = (this.type === 'school') ? this.schoolService.schoolCommon$ : this.teamService.teamCommon$;
            this.typeSubsriber$ = this.typeSubsriber$.filter(rez => rez).subscribe(response => {
                if (response) {
                    this.typeId = response.id;
                    this.loadGames(false);
                }
            });
        } else {
            this.loadGames(false);
        }

        // load games on change sport
        if (this.type === 'school') {
            this.sportSubsriber$ = this.rootService.sportChange$.filter(sport => sport).subscribe(() => {
                this.loadGames(false);
            });
        }

    }

    loadGames(allGames) {
        this.allGames = allGames;
        this.scheduleService.getFullTimeTable(allGames, this.type, this.typeId, this.sportId).subscribe((rez) => {
            this.remainder = rez.remainder;
            this.games = [];
            _.forEach(rez.data, v => {
                const newGame = new Game(v);

                this.games.push(newGame);
            });
            this.divideGames();
        });
    }

    divideGames(games?) {
        games = (games) ? games : this.games;
        if (games) {
            this.dividedGames = _.chain(games).chunk(10).map((v) => {
                return {
                    games: v,
                    headerDates: this.getDateInterval(v)
                };
            }).value();
        }
    }

    getDateInterval(games) {
        if (games.length > 1) {
            return {
                begin: games[0].dateObj(),
                end: games[games.length - 1].dateObj()
            };
        } else {
            return {
                begin: games[0].dateObj(),
            };
        }
    }

    filterVariants(value = '') {
        const filteredGames = _.filter(this.games, (game) => {
            let isFav = true;
            if (this.isSelectMode) {
                switch (this.type) {
                    case 'team' :
                        isFav = this.userService.isFavorite('teams', game.opponentTeam.id);
                        break;
                    default :
                        isFav = this.userService.isFavorite('teams', game.opponentTeam.id) ||
                            this.userService.isFavorite('teams', game.team.id);
                }
            }
            return value.length > 2 ? this.searchInGame(game, value) && isFav : isFav;
        });

        return value.length > 2 || this.isSelectMode ?  this.divideGames(filteredGames) : this.divideGames();
    }

    searchInGame(game, value) {
        value = value.toLowerCase();

        let concatStr = (game.where == 'away') ? '@' : 'vs';
        let str1 = game.team.shortName.toLowerCase() + ' ' + concatStr + ' ' + game.opponentTeam.shortName.toLowerCase();
        let str2 = game.team.name.toLowerCase() + ' ' + concatStr + ' ' + game.opponentTeam.name.toLowerCase();
        if (str1.indexOf(value) == 0 || str2.indexOf(value) == 0) {
            return true;
        }
        //by game type
        else if (game.getFullType().indexOf(value) == 0) {
            return true;
        }
        //location
        if (new RegExp("\\b" + value + "(.*)").test(game.location.toLowerCase())) {
            return true;
        }
        this.datePipe.transform(game.dateObj(), 'shortTime');
        //date
        if (value == this.datePipe.transform(game.dateObj(), 'MMM d').toLowerCase()
            || value == this.datePipe.transform(game.dateObj(), 'MMMM d').toLowerCase()
            || value == this.datePipe.transform(game.dateObj(), 'MMM dd').toLowerCase()
            || value == this.datePipe.transform(game.dateObj(), 'MMMM dd').toLowerCase()
            || value == this.datePipe.transform(game.dateObj(), 'MMMM dd').toLowerCase()) {
            return true;
        }
        return false;
    }

    ngOnDestroy() {
        if (this.sportSubsriber$) {
            this.sportSubsriber$.unsubscribe();
        }
        if (this.typeSubsriber$) {
            this.typeSubsriber$.unsubscribe();
        }
    }

}
