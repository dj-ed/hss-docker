
import { Injectable } from '@angular/core';
import { CookieService } from 'ngx-cookie-service';

@Injectable()
export class CookieStorage implements Storage {

    [index: number]: string;
    [key: string]: any;
    length: number;

    constructor(private cookieService: CookieService) {

    }

    clear(): void {
        this.cookieService.deleteAll();
    }

    getItem(key: string): string {
        const result = this.cookieService.get(key);

        return result;
    }

    key(index: number): string {
        const result = this.cookieService.getAll().propertyIsEnumerable[index];

        return result;
    }

    removeItem(key: string): void {
        this.cookieService.delete(key, '/');
    }

    setItem(key: string, data: string): void {
        const now = new Date();
        let time = now.getTime();
        now.setTime(time += 3600 * 1000 * 24 * 365);

        this.cookieService.set(key, data,  now, '/');
    }

    getAll() {
        this.cookieService.getAll();
    }

}
