import { Injectable } from '@angular/core';
import * as Recorder from 'opus-recorder/dist/recorder.min.js';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/observable/interval';
import { Subscription } from 'rxjs/Subscription';

@Injectable()
export class AudioRecordingService {
    static audioFile: any;
    rec: any;
    isSupported: boolean = false;
    recordingStarted: boolean = false;
    recordTimer: Subscription;
    timerMinutes: string;
    timerSeconds: string;

    constructor() {
        this.isSupported = Recorder.isRecordingSupported();
    }

    startRecord() {
        this.rec = new Recorder({
            numberOfChannels: 1,
            encoderSampleRate: 48000,
            resampleQuality: 8,
            encoderPath: '/js/encoderWorker.min.js'
        });

        this.rec.addEventListener('start', () => {
            const recordingIcon = document.querySelector('.recording-icon');
            this.recordingStarted = true;
            this.timerMinutes = '00';
            this.timerSeconds = '00';

            this.recordTimer = Observable.interval(1000).subscribe(next => {
                const minutes = Math.floor(((next + 1) % (60 * 60)) / 60);
                const seconds = Math.floor((next + 1) % 60);
                this.timerMinutes = (minutes < 10) ? '0' + minutes : minutes.toString();
                this.timerSeconds = (seconds < 10) ? '0' + seconds : seconds.toString();
                recordingIcon.classList.toggle('record-opacity');
            });
        });

        this.rec.addEventListener('streamError', e => {
            // TODO: popup error
            console.log('Error encountered: ' + e.error.name);
        });

        this.rec.addEventListener('dataAvailable', e => {
            AudioRecordingService.audioFile = new Blob([e.detail], {type: 'audio/ogg'});
        });

        this.rec.initStream().then(() => {
            this.rec.start();
        });
    }

    stopRecord() {
        this.rec.stop();
        this.recordingStarted = false;
        this.recordTimer.unsubscribe();
    }

    disableRecord() {
        this.rec.clearStream();
        this.recordingStarted = false;
        this.recordTimer.unsubscribe();
    }

}
