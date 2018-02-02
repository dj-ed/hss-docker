import { Injectable } from '@angular/core';
import { AjaxService } from '../../services/ajax.service';
import { Comment } from '../../../models/comment.model';
import { SimpleSelectOption } from '../../../models/simple-select-option.model';
import * as _ from 'lodash';

@Injectable()
export class CommentService {
    commentsList: Comment[] = [];
    orderOptions: SimpleSelectOption[] = [];
    currentOrder: string;
    commentsLoaded: boolean = false;
    openedReportAbusePopups: any;

    constructor(public ajaxService: AjaxService) {
        this.orderOptions = [];
        _.forEach(['Most Recent', 'Most Top', 'Most Discussed', 'None'], item => {
            const object = new SimpleSelectOption({value: item, label: item});
            this.orderOptions.push(object);
        });
    }

    getComments(modelType, modelId) {
        this.commentsLoaded = false;
        return this.ajaxService.post('comment/get-comments', {
            modelType, modelId
        }).subscribe(response => {
            this.commentsList = [];
            _.forEach(response.data, comment => {
                this.commentsList.push(new Comment(comment));
            });
            this.changeOrderComments();
            this.commentsLoaded = true;
        });
    }

    // Most TOP - more likes on top
    // Most Discussed - more reply on top
    // Most Recent - new on top
    // None - new on bottom
    changeOrderComments() {
        switch (this.currentOrder) {
            case 'Most Top':
                this.commentsList = _.reverse(_.sortBy(this.commentsList, ['likes']));
                break;
            case 'Most Discussed':
                this.commentsList = _.reverse(_.sortBy(this.commentsList, [i => i.replies.length]));
                break;
            case 'None':
                this.commentsList = _.sortBy(this.commentsList, ['id']);
                break;
            case 'Most Recent':
                this.commentsList = _.reverse(_.sortBy(this.commentsList, ['id']));
                break;
        }
    }

    postTextComment(text, modelType, modelId, currentReply) {
        return new Promise((resolve, reject) => {
            this.ajaxService.post('comment/post-text', {
                text,
                modelType,
                modelId,
                reply: currentReply
            }).subscribe(result => {
                if (result) {
                    this.addCommentToList(result);
                    resolve(result);
                } else {
                    // TODO: change to popup
                    alert('Server error!');
                    reject();
                }
            });
        });
    }

    postAudioComment(url, modelType, modelId, currentReply) {
        return new Promise((resolve, reject) => {
            this.ajaxService.file('comment/post-audio', {
                file: url,
                modelType,
                modelId,
                reply: currentReply
            }).subscribe(result => {
                if (result) {
                    this.addCommentToList(result);
                    resolve(result);
                } else {
                    // TODO: change to popup
                    alert('Server error!');
                    reject();
                }
            });
        });
    }

    addCommentToList(comment) {
        if (comment.replyId) {
            // add reply
            const item = _.find(this.commentsList, ['id', +comment.replyId]);
            item.replies.push(new Comment(comment));
        } else {
            this.commentsList.push(new Comment(comment));
        }

        this.changeOrderComments();
    }

    addLike(commentId) {
        return this.ajaxService.post('comment/add-like', {
            id: commentId,
        });
    }

    removeLike(commentId) {
        return this.ajaxService.post('comment/remove-like', {
            id: commentId,
        });
    }

    openReportAbuse(popup) {
        if (this.openedReportAbusePopups) {
            this.openedReportAbusePopups.hide();
        }

        this.openedReportAbusePopups = popup;
        popup.show();
    }

    reportAbuse(commentId, selectedType) {
        return this.ajaxService.post('comment/report-abuse', {
            id: commentId,
            reportType: selectedType
        });
    }

}
