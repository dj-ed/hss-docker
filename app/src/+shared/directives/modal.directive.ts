import {
    Directive, ElementRef, EventEmitter, OnDestroy, OnInit, Renderer, TemplateRef, ViewContainerRef
} from '@angular/core';
import {Observable} from "rxjs/Observable";
import { Component,Input, Output, HostListener, HostBinding, trigger, transition, animate, style, state} from '@angular/core';

@Directive({
    selector: '[modal]',
    outputs: ['close']
    })
export class ModalDirective implements OnInit, OnDestroy{
    isWork = true;
    openedFullScreenInside = false;
    isUsingModal = false;
    close$: EventEmitter<any> = new EventEmitter();
    constructor(public elem: ElementRef, public templateRef: TemplateRef<any>, public viewContainer: ViewContainerRef, public renderer: Renderer) {
    }
    close() {
        document.body.style.overflow = '';
        this.viewContainer.clear();
        this.close$.next();
        this.isUsingModal = false;
    }

    open() {
        document.body.style.overflow = 'hidden';
        this.viewContainer.clear();
        this.viewContainer.createEmbeddedView(this.templateRef);
        this.isUsingModal = true;
    }

    ngOnInit() {
        this.viewContainer.clear();
        Observable.fromEvent(document.body, 'keyup').takeWhile(() => this.isWork)
            .filter((e: KeyboardEvent) => e.keyCode === 27 && !this.openedFullScreenInside && this.isUsingModal)
            .subscribe(() => {
                this.close();
            });
        Observable.fromEvent(document.body, 'mousedown').takeWhile(() => this.isWork)
            .filter((e: MouseEvent) =>  {
               const t = e.target as HTMLElement;
               return t.classList.contains('modalWindow') && this.isUsingModal;
        }).subscribe(() => {
                this.close();
            });
    }

    ngOnDestroy() {
       this.isWork = false;
    }
}