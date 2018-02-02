import {Injectable} from '@angular/core';

@Injectable()
export class AllTeamsSchoolsService {
    constructor() {

    }

    sortUnique(data, key) {
        let chars = [];
        let isNumberExist = false;
        data.forEach(item => {
            item[key] = item[key].toLowerCase();
            if (chars.indexOf(item[key][0]) === -1) {
                isNumberExist = Number.isInteger(+item[key][0]) ? true : isNumberExist;
                if (!Number.isInteger(+item[key][0])) {
                    chars.push(item[key][0]);
                }
            }
        });
        chars = chars.sort((ch1, ch2) => ch1 > ch2 ? 1 : ch1 < ch2 ? -1 : 0);
        if (isNumberExist) {
            chars.push('#');
        }
        return chars;
    }

    sort(data, key, order) {
        return data.sort((item1, item2) => {
            if (Number.isInteger(+item1[key][0]) || Number.isInteger(+item2[key][0])) {
                return this.sortNumbers(item1[key][0], item2[key][0]);
            } else {
                return this.sortStrings(item1[key], item2[key], order);
            }
        });
    }

    getItemsByChar(data, key, char) {
        return data.filter((item) => {
            if (char !== '#') {
                return item[key][0].toLowerCase() === char.toLowerCase();
            } else {
                return Number.isInteger(+item[key][0]);
            }
        });

    }

    sortNumbers(firstItemChar, secondItemChar) {
        const isF = Number.isInteger(+firstItemChar) || firstItemChar === '#';
        const isS = Number.isInteger(+secondItemChar) || secondItemChar === '#';
        if (isF && !isS) {
            return 1;
        } else if (!isF && isS) {
            return -1;
        } else {
            return +firstItemChar > +secondItemChar ? 1 : +firstItemChar < +secondItemChar ? -1 : 0;
        }
    }

    sortStrings(firstString, secondString, order) {
        firstString = firstString.toLowerCase();
        secondString = secondString.toLowerCase();
        if (order === 'a-z') {
            if (firstString < secondString) return -1;
            if (firstString > secondString) return 1;
            return 0;
        } else {
            if (firstString > secondString) return -1;
            if (firstString < secondString) return 1;
            return 0;
        }
    }

    searchInString(string, searchText) {
        string = (string + '').toLowerCase();
        searchText = searchText.toLowerCase();
        return string.toLowerCase().indexOf(searchText) === 0;
    }

    searchNumber(string, searchText) {
        string = (string + '').toLowerCase();
        searchText = searchText.toLowerCase();
        return Number.isInteger(+string) ? searchText === string : false;
    }

    searchInArray(array, searchText) {
        searchText = searchText.toLowerCase();
        return array.filter(item => item.toLowerCase().indexOf(searchText) === 0).length;
    }

    runSearch(data, params, searchText, searchTypes) {
        let se = data.map(levelData => {
            let foundSomething;
            params.searchKeys.forEach(searchParam => {
                foundSomething = searchTypes[searchParam.type](levelData[searchParam.name], searchText) ? true : foundSomething;
                if (!foundSomething) {
                    if (params.contains) {
                        if(params.contains.fetch) {
                            levelData[params.contains.key] = params.contains.fetch(levelData);
                        }
                        let result = this.runSearch(levelData[params.contains.key], params.contains.structure, searchText, searchTypes);
                        levelData[params.contains.key] = result.length ? result : [];
                        foundSomething = result.length;
                    }
                }
            });
            if (!params.searchKeys.length) {
                if (params.contains) {
                    if(params.contains.fetch) {
                        levelData[params.contains.key] = params.contains.fetch(levelData);
                    }
                    let result = this.runSearch(levelData[params.contains.key], params.contains.structure, searchText, searchTypes);
                    levelData[params.contains.key] = result.length ? result : [];
                    foundSomething = result.length;
                }
            }

            return foundSomething ? levelData : null;
        }).filter(levelData => {
            if (levelData) {
                if (params.countParam) {
                    levelData[params.countParam.name] =  params.countParam.func(levelData, params, searchText);
                }
            }
            return levelData
        });
        return se;
    }

}