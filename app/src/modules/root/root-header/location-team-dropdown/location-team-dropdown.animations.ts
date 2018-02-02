import { trigger, style, animate, transition } from '@angular/animations';

export const apperaAnimation = trigger(
    'apperaAnimation', [
        transition(':enter', [
            style({opacity: 0}),
            animate('180ms', style({opacity: 1}))
        ]),
        transition(':leave', [
            style({opacity: 1}),
            animate('180ms', style({opacity: 0}))
        ])
    ]
)