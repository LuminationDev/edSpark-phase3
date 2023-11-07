export type BasePostType = {
    content: string;
    title: string;
    cover_image: string | null;
    excerpt: string;
    extra_content: string | TransformedData | null;
    id: number;
    type: string;
    post_type: string;
    created_at: string;
    updated_at: string;
    isLikedByUser?: boolean;
    isBookmarkedByUser?: boolean;
    tags?: string[]
};


export type TemplateType = 'Extraresource' | 'Numbereditems' | 'Dateitems';

export interface ContentItem {
    icon?: string;
    start_date?: string
    content?: string;
    heading?: string;
}

export interface TransformedData {
    data: {
        template: string;
        extra_content: {
            [key: string]: {
                item: ContentItem[];
                title?: string;
            };
        };
    };
    type: string;
}

export interface SimpleDataItem {
    template: TemplateType;
    title?: string;
    content: ContentItem[];
}


export type ExtraContentFilamentType = {
    data: {
        template: string;
        extra_content: {
            date_items?: {
                item: {
                    content: string;
                    heading: string;
                    start_date: string;
                }[];
            };
        };
    };
    type: string;
};
