import { trigger, style, transition, animate } from '@angular/core';

export const Animations = {
    animeTrigger: trigger(
        'slideDownAnimation', [
            transition(':enter', [
                style({'max-height': 0}),
                animate('180ms 240ms', style({'max-height': 460}))
            ]),
            transition(':leave', [
                style({'max-height': 460}),
                animate('180ms', style({'max-height': 0}))
            ])
        ]
    )
}