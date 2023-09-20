import Post from "@/js/models/post";

export interface EventLocationType {
    url? : string,
    address?: string
}
export default class Event extends Post {
    eventtype_id: number
    eventLocation: EventLocationType
    startDate: string
    endDate: string

    constructor(title: string,
                excerpt: string,
                content: string,
                coverImage: string,
                authorName: string,
                tags: Array<string>,
                extra_content: string,
                eventLocation: EventLocationType,
                eventtype_id: number,
                startDate: string,
                endDate: string) {
        super(title, excerpt, content, coverImage, authorName, tags, extra_content)
        this.eventtype_id = eventtype_id
        this.eventLocation = eventLocation
        this.startDate = startDate
        this.endDate = endDate
    }

}
