import { trigger, state, style, transition, animate } from '@angular/animations';

export const changeStateTrigger = trigger('changeTrigger', [
        state('hidden', style({
            'width': '0',
            'display': 'none',
            'padding-right': '0',
            'padding-left': '0',
            'margin-left': '0',
            'margin-right': '0'
        })),
        state('visible', style({
            'width': '200px',
            'display': 'inline-block',
            'padding-right': '20px',
            'padding-left': '8px',
            'margin-left': '17px',
            'margin-right': '-4px'
        })),
        transition('visible <=> hidden', animate('320ms ease-in-out'))
    ]);

export const clearBtnStateTrigger = trigger('clearBtnTrigger', [
        state('hidden', style({
            'opacity': '0',

        })),
        state('visible', style({
            'opacity': '1',

        })),
        transition('visible <=> hidden', animate('200ms ease-in-out'))
    ]);

export const searchIconStateTrigger = trigger('searchIconTrigger', [
        state('glass', style({
            background: 'url(/img/search-dark.svg)no-repeat center right / contain',
        })),
        state('cross', style({
            background: 'url(/img/close-dark.svg)no-repeat center right / contain'
        })),
        transition('glass <=> cross', animate('200ms ease-in-out'))
    ]);
