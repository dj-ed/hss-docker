"use strict";
var __decorate = (this && this.__decorate) || function (decorators, target, key, desc) {
    var c = arguments.length, r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc, d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") r = Reflect.decorate(decorators, target, key, desc);
    else for (var i = decorators.length - 1; i >= 0; i--) if (d = decorators[i]) r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};
var __metadata = (this && this.__metadata) || function (k, v) {
    if (typeof Reflect === "object" && typeof Reflect.metadata === "function") return Reflect.metadata(k, v);
};
Object.defineProperty(exports, "__esModule", { value: true });
var core_1 = require("@angular/core");
var ajax_service_1 = require("../../+shared/services/ajax.service");
var school_model_1 = require("../../models/school.model");
var BehaviorSubject_1 = require("rxjs/BehaviorSubject");
var root_service_1 = require("../root/root.service");
var _ = require("lodash");
var SchoolService = (function () {
    function SchoolService(ajaxService, rootService) {
        this.ajaxService = ajaxService;
        this.rootService = rootService;
        this.schoolCommon$ = new BehaviorSubject_1.BehaviorSubject(undefined);
    }
    SchoolService.prototype.loadSchoolCommon = function (schoolId) {
        var _this = this;
        this.schoolCommon$.next(undefined);
        this.ajaxService.post('school', { schoolId: schoolId }).subscribe(function (response) {
            _this.schoolCommon$.next(new school_model_1.School(response));
        });
    };
    SchoolService.prototype.subscribeSchool = function () {
        var _this = this;
        return this.rootService.ready$
            .filter(function (isReady) { return isReady; })
            .concatMap(function () {
            return _this.schoolCommon$.filter(function (school) { return !_.isUndefined(school); });
        });
    };
    SchoolService.prototype.loadSchoolSports = function (schoolId) {
        return this.ajaxService.post('school/sports', { schoolId: schoolId });
    };
    SchoolService.prototype.loadSchoolInfo = function (schoolId) {
        return this.ajaxService.post('school/info', { schoolId: schoolId });
    };
    return SchoolService;
}());
SchoolService = __decorate([
    core_1.Injectable(),
    __metadata("design:paramtypes", [ajax_service_1.AjaxService, root_service_1.RootService])
], SchoolService);
exports.SchoolService = SchoolService;
//# sourceMappingURL=school.service.js.map