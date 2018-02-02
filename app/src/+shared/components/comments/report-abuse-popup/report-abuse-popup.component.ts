import { Component, Input } from '@angular/core';
import { CommentService } from '../comments.service';

@Component({
    selector: 'report-abuse-popup',
    templateUrl: './report-abuse-popup.component.html'
})
export class ReportAbusePopupComponent {
    @Input() commentId;
    visible: boolean = false;
    sent: boolean = false;
    selectedType: string;

    constructor(public commentService: CommentService) {
    }

    show() {
        this.visible = true;
    }

    hide() {
        this.visible = false;
    }

    report() {
        this.sent = true;
        this.commentService.reportAbuse(this.commentId, this.selectedType).subscribe((result) => {
            if (result.success) {
                setTimeout(() => {
                    this.hide();
                }, 3000);
            } else {
                this.sent = false;
                // TODO: change to popup
                alert('Server error!');
            }
        });
    }
}
