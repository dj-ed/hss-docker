import { Component, EventEmitter, Input, Output } from '@angular/core';
import { CommentService } from '../comments.service';
import { UserService } from '../../../services/user.service';

@Component({
    selector: 'one-comment',
    templateUrl: './one-comment.component.html'
})
export class OneCommentComponent {
    @Input() comment;
    @Output() reply = new EventEmitter();

    constructor(public commentService: CommentService, public userService: UserService) {
    }

    addLike() {
        this.comment.isVoted = true;
        this.comment.likes++;
        this.commentService.addLike(this.comment.id).subscribe(result => {
            if (!result.success) {
                this.comment.isVoted = false;
                this.comment.likes--;
                alert('Server error');
            }
        });
    }

    removeLike() {
        this.comment.isVoted = false;
        this.comment.likes--;
        this.commentService.removeLike(this.comment.id).subscribe(result => {
            if (!result.success) {
                this.comment.isVoted = true;
                this.comment.likes++;
                alert('Server error');
            }
        });
    }

    callReply(){
        this.reply.emit(this.comment.id);
    }
}
