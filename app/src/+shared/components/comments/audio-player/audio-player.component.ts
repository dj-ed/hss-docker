import { AfterViewInit, Component, Input, ViewChild } from '@angular/core';

@Component({
    selector: 'audio-player',
    templateUrl: './audio-player.component.html'
})
export class AudioPlayerComponent implements AfterViewInit {
    @Input() audio;
    isPlayed: boolean = false;
    duration: number;
    @ViewChild('player') player;
    @ViewChild('timeline') timeline;
    @ViewChild('playPosition') playPosition;
    timelineWidth = 260;

    ngAfterViewInit(): void {
        this.player.nativeElement.addEventListener('canplaythrough', (e) => {
            this.duration = e.target.duration;
        }, false);

        this.player.nativeElement.addEventListener('timeupdate', () => {
            const currentTime = this.player.nativeElement.currentTime;
            const playPercent = this.timelineWidth * (currentTime / this.duration);
            this.playPosition.nativeElement.style.marginLeft = playPercent + 'px';
        }, false);

        this.player.nativeElement.addEventListener('ended', () => {
            this.isPlayed = false;
        }, false);
    }

    play() {
        this.isPlayed = true;
        this.player.nativeElement.play();
    }

    pause() {
        this.resetTransition();
        this.isPlayed = false;
        this.player.nativeElement.pause();
    }

    resetTransition() {
        const playClass = this.playPosition.nativeElement.classList;
        playClass.add('no-transition');
        setTimeout(() => {
            playClass.remove('no-transition');
        }, 10);
    }

    timelineClick(e) {
        this.resetTransition();

        const newMargLeft = e.clientX - this.getPosition(this.timeline.nativeElement);
        const playStyle = this.playPosition.nativeElement.style;
        if (newMargLeft >= 0 && newMargLeft <= this.timelineWidth) {
            playStyle.marginLeft = newMargLeft + 'px';
        }
        if (newMargLeft < 0) {
            playStyle.marginLeft = '0px';
        }
        if (newMargLeft > this.timelineWidth) {
            playStyle.marginLeft = this.timelineWidth + 'px';
        }
        this.player.nativeElement.currentTime = this.duration * this.clickPercent(e);
    }

    private clickPercent(e) {
        return (e.clientX - this.getPosition(this.timeline.nativeElement)) / this.timelineWidth;
    }

    private getPosition(el) {
        return el.getBoundingClientRect().left;
    }

}
