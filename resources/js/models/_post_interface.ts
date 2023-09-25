interface PostInterface {
    title: string,
    excerpt: string,
    content: string,
    coverImage: string,
    authorName: string,
    tags: Array<string>
    extraContent: string
}

export default PostInterface