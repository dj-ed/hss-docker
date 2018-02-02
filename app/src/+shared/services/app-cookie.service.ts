import { Inject, Injectable } from '@angular/core';
import { AppStorage } from "../../forStorage/universal.inject";


@Injectable()
export class AppCookieService {

    constructor(@Inject(AppStorage) private appStorage: Storage) {
    }

    set(key, value) {
        this.appStorage.setItem(key, value)
    }

    get(key): any {
       return  this.appStorage.getItem(key)
    }

    remove(key) {
        this.appStorage.removeItem(key)
    }

    getAll() {
        return this.appStorage.getAll();
    }


}
