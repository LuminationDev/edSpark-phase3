import Post from "@/js/models/post";

export default class Advice extends Post {
    advicetype_id: Array<number>
    constructor(title: string, excerpt: string, content: string, coverImage: string, authorName: string, tags: Array<string>, extra_content: string, advicetype_id: Array<number>) {
        super(title, excerpt, content, coverImage, authorName, tags, extra_content)
        this.advicetype_id = advicetype_id
    }

}
