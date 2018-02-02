import {Component, EventEmitter, Input, Output, ViewChild} from '@angular/core';
import { CommentService } from './comments.service';
import { AudioRecordingService } from './audio-recording.service';
import { Observable } from 'rxjs/Observable';
import { Comment } from '../../../models/comment.model';
import { UserService } from '../../services/user.service';

@Component({
    selector: 'comments-component',
    templateUrl: './comments.component.html',
    styleUrls: ['./comments.component.scss'],
})
export class CommentsComponent {
    @Input() modelId: number;
    @Input() modelType: string;
    @Output() closeComment: EventEmitter<any> = new EventEmitter();
    @ViewChild('textInput') textInput;
    @ViewChild('commentsBlock') commentsBlock;
    showComments: boolean = false;
    currentReply: number;

    constructor(public commentService: CommentService, public audioRecordingService: AudioRecordingService,
                public userService: UserService) {
    }

    getComments(param: {type: string, id: number} = {type: this.modelType, id: this.modelId}, forcibly?: boolean) {
        if (!this.showComments || forcibly) {
            this.commentService.getComments(param.type, param.id);
            this.showComments = true;
        }
    }

    hide() {
        this.showComments = false;
    }

    addText() {
        const input = this.textInput.nativeElement;
        if (input.value.length) {
            this.commentService.postTextComment(input.value, this.modelType, this.modelId, this.currentReply).then((comment: Comment) => {
                this.scrollToComment(comment.id);
            });
            input.value = '';
            this.currentReply = undefined;
        }
    }

    addAudion() {
        this.audioRecordingService.stopRecord();

        const fileWatcher = Observable.interval(200).subscribe(() => {
            if (AudioRecordingService.audioFile !== undefined) {
                fileWatcher.unsubscribe();
                this.commentService.postAudioComment(AudioRecordingService.audioFile, this.modelType, this.modelId, this.currentReply)
                    .then((comment: Comment) => {
                        this.scrollToComment(comment.id);
                    });
                this.currentReply = undefined;
            }
        });
    }

    changeOrder(value) {
        this.commentService.currentOrder = value;
        this.commentService.changeOrderComments();
    }

    reply(e) {
        const input = this.textInput.nativeElement;
        this.currentReply = e;
        input.focus();
    }

    private scrollToComment(commentId) {
        setTimeout(() => {
            const comment: any = document.querySelectorAll(`.one-comment[data-id="${commentId}"]`);
            this.commentsBlock.nativeElement.scrollTop = comment[0].offsetTop;
        }, 50);
    }

}
