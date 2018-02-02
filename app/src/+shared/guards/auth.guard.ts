import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, CanActivateChild, Router } from '@angular/router';
import { UserService } from '../services/user.service';

@Injectable()
export class CanActivateAuthGuard implements CanActivate, CanActivateChild {

    constructor(private userService: UserService, private router: Router) {
    }

    canActivate(route: ActivatedRouteSnapshot): boolean {
        if (this.userService.isLoggedIn()) {
            return true;
        }

        this.router.navigate(['/']);
        return false;
    }

    canActivateChild(route: ActivatedRouteSnapshot): boolean {
        return this.canActivate(route);
    }
}
