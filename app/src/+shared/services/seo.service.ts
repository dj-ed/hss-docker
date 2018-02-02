import { Meta, Title } from '@angular/platform-browser';
import { Injectable } from '@angular/core';

@Injectable()
export class SeoService {

    constructor(private title: Title, private meta: Meta) {
    }

    setTitle(title: string): SeoService {
        this.title.setTitle(title);
        return this;
    }

    setDescription(description: string): SeoService {
        this.meta.updateTag({
            name: 'description',
            content: description
        });
        return this;
    }
}
