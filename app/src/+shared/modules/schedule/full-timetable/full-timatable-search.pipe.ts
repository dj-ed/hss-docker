
import * as _ from 'lodash';
import {UserService} from "../../../services/user.service";
import {DatePipe} from "@angular/common";

export class FullTimatableSearchPipe {

    isSelectMode;
    searchText;

    constructor(public userService: UserService, public datePipe: DatePipe) {

    }
    transform(data, searchText, isSelectMode) {
        this.searchText = searchText.toLowerCase();
        this.isSelectMode = isSelectMode;

    }
}