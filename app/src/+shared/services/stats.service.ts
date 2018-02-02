import { Injectable } from '@angular/core';
import { AjaxService } from './ajax.service';
import { RootService } from '../../modules/root/root.service';
import { TeamService } from '../../modules/team/team.service';
import { PlayerService } from "../../modules/player/player.service";
@Injectable()

export class StatsService {

    constructor(public ajaxService: AjaxService, public rootService: RootService, private teamService: TeamService, private playerService: PlayerService) {
    }

    laodLeaderboard(teamStats?) {
        let data = {};
        return this.rootService.ready$.filter(isReady => isReady).map(rez => {
            data = this.compactRootData();
            if (teamStats) {
                return this.teamService.subscribeTeam().concatMap(team => {
                    data['teamId'] = team.id;
                    return this.ajaxService.post('statistics/leaderboard', data);
                });
            } else {
                return this.ajaxService.post('statistics/leaderboard', data);
            }
        }).concatAll();
    }

    loadScoreboard(page, sectionType?) {
        let data = {};
        return this.rootService.ready$.filter(isReady => isReady).map(rez => {
                data = this.compactRootData();
                data['page'] = page;

                if (sectionType === 'team') {
                    return this.teamService.subscribeTeam().concatMap(team => {
                        data['teamId'] = team.id;
                        return this.ajaxService.post('statistics/scoreboard', data);
                    });
                }
                else if (sectionType === 'player') {
                    return this.playerService.subscribePlayer().concatMap(player => {
                        data['teamId'] = player.teamId;
                        return this.ajaxService.post('statistics/scoreboard', data);
                    });
                } else {
                    return this.ajaxService.post('statistics/scoreboard', data);
                }

            }
        ).concatAll();

    }

    loadSportScoreboard() {
        let data = {};
        data = this.compactRootData();
        return this.ajaxService.post('statistics/sport-scoreboard', data);
    }

    loadSportLeaderboard(type?) {
        let data = {};
        data = this.compactRootData();
        if (type) {
            data['genderId'] = type.gender.id;
            data['varsityId'] = type.teamType.id;
            data['withoutTypes'] = true;
        }
        return this.ajaxService.post('statistics/sport-leaderboard', data);
    }

    loadSportTeamStandings(type?) {
        let data = {};
        data = this.compactRootData();
        if (type) {
            data['genderId'] = type.gender.id;
            data['varsityId'] = type.teamType.id;
            data['withoutTypes'] = true;
        }
        return this.ajaxService.post('statistics/sport-team-standings', data);

    }

    loadFullBoardData(page) {

        let data = {};
        return this.rootService.ready$.filter(isReady => isReady).map(rez => {
            data = this.compactRootData();
            data['page'] = page;

            return this.ajaxService.post('statistics/full-board', data);

        }).concatAll();
    }

    loadStandings(type?) {
        let data = {};
        if (type === 'team') {
            return this.teamService.subscribeTeam().concatMap(team => {
                data['teamId'] = team.id;
                return this.ajaxService.post('statistics/standings', data);
            });
        }
        else if (type == 'player') {
            return this.playerService.subscribePlayer().concatMap(player => {
                data['teamId'] = player.teamId;
                return this.ajaxService.post('statistics/standings', data);
            });
        }
        else {
            return this.rootService.ready$.filter(isReady => isReady).map(rez => {
                data = this.compactRootData();
                return this.ajaxService.post('statistics/standings', data);
            }).concatAll();
        }
    }


    compactRootData() {
        return {
            genderId: this.rootService.currentGenderId,
            seasonId: this.rootService.currentSeasonId,
            sportId: this.rootService.currentSportId,
            varsityId: this.rootService.currentVarsityId,
        };
    }

    loadGameDetail(gameId) {
        return this.ajaxService.post('statistics/scoreboard-game-detail', {gameId});
    }

}