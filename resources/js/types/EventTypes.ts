import {ExtraContentFilamentType} from "@/js/types/SharedTypes";

export type EventType = {
    id: number;
    title: string;
    content: string;
    excerpt: string;
    location: {
        url: string;
        address?: string;
    };
    author: {
        author_id: number;
        author_name: string;
        author_email: string;
        author_type: string;
        author_logo: string;
    };
    cover_image: string;
    start_date: string;
    end_date: string;
    status: string;
    type: string;
    created_at: string;
    updated_at: string;
    extra_content: ExtraContentFilamentType[];
    isLikedByUser: boolean;
    isBookmarkedByUser: boolean;
    tags: string[];
};

interface EventLocationType {
    url?: string,
    address?: string
}

export type EventAdditionalData = {
    extraContentData: Array<any>,
    eventType: number,
    eventLocation: EventLocationType
    startTime: Date,
    endTime: Date
}