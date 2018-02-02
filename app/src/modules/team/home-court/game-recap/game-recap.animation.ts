import { trigger, style, transition, animate } from '@angular/core';

export const Animations = {
    animeTrigger: trigger(
        'slideDownAnimation', [
            transition(':enter', [
                style({'max-height': 0}),
                animate('360ms', style({'max-height': 850}))
            ]),
            transition(':leave', [
                style({'max-height': 850}),
                animate('360ms', style({'max-height': 0}))
            ])
        ]
    )
}