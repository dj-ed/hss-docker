import { Component } from '@angular/core';
import { SeoService } from '../../services/seo.service';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import {UserService} from "../../services/user.service";

@Component({
    selector: 'auth-component',
    templateUrl: './auth.component.html',
})
export class AuthComponent {

    loginForm: FormGroup;
    loginFormErrors: string[] = [];
    // validationMessages = {
    //     login: {
    //         required: 'Enter login'
    //     },
    //     password: {
    //         required: 'Enter Password'
    //     }
    // };

    constructor(public seoService: SeoService, public userService: UserService, private fb: FormBuilder) {
        seoService.setTitle('Login');

        this.loginForm = this.fb.group({
            login: ['', Validators.required],
            password: ['', Validators.required]
        });
    }

    login() {
        console.log(this.loginForm.valid);
        if (this.loginForm.valid) {
            this.userService.login(this.loginForm.get('login').value, this.loginForm.get('password').value);
        }
        // this.onValueChanged();
    }

    // onValueChanged() {
    //     const form = this.loginForm;
    //     this.loginFormErrors = [];
    //
    //     for (const key in form.controls) {
    //         const control = form.get(key);
    //
    //         if (control && !control.valid) {
    //             if (form.controls[key].errors) {
    //                 for (const error in form.controls[key].errors) {
    //                     this.loginFormErrors.push(this.validationMessages[key][error]);
    //                 }
    //             }
    //         }
    //     }
    // }
}
