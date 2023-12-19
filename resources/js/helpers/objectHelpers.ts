import purify from "dompurify";

export const findNestedKeyValue = (obj, key) => {
    const result = [];

    const traverse = (data) => {
        if (typeof data !== 'object' || data === null) {
            return;
        }

        if (Array.isArray(data)) {
            data.forEach((item) => traverse(item));
        } else {
            if (data.hasOwnProperty(key)) {
                result.push(data[key]);
            }

            for (const prop in data) {
                traverse(data[prop]);
            }
        }
    };
    traverse(obj);
    return result;
}

export const isObjectEmpty = (objectName) => {
    return Object.keys(objectName).length === 0
}

export const safelyExtractFirstObjectFromArray = (array) => {
    if (array.length > 1) {
        throw Error('Array has more than 1 object')
    } else {
        return array[0]
    }
}

export const isEmptyFunction = (func): boolean => {
    const functionAsString = func.toString();

    // Remove whitespace and check if it matches the pattern of an empty function
    return /^(function\s?\(\)\s?\{\s?\}|^\(\s?\)\s?=>\s?\{\s?\})$/.test(functionAsString);
}


/**
 * Takes dirty content and sanitize it while keeping Iframes for embedding
 * @param dirty : String
 */
export const edSparkContentSanitizer = (dirty) => {
    return purify.sanitize(dirty, {
        ADD_TAGS: ["iframe"],
        ADD_ATTR: ['allow', 'allowfullscreen', 'frameborder', 'scrolling']
    })
}

export const convertLinksToEmbeds = (content) => {
    // YouTube
    const regexYoutube = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com|youtu\.be)\/(?:watch\?v=)?([\w\-]{11})/g;
    content = content.replace(regexYoutube, (match, p1) => {
        return `<iframe width="560" height="315" src="https://www.youtube.com/embed/${p1}" frameborder="0" allowfullscreen></iframe>`;
    });

    // Vimeo
    const regexVimeo = /https?:\/\/(?:www\.)?vimeo\.com\/(\d+)/g;
    content = content.replace(regexVimeo, (match, p1) => {
        return `<iframe src="https://player.vimeo.com/video/${p1}" width="560" height="315" frameborder="0" allowfullscreen></iframe>`;
    });

    // Twitter
    const regexTwitter = /https?:\/\/twitter\.com\/(?:\w+)\/status\/(\d+)/g;
    content = content.replace(regexTwitter, (match, p1) => {
        return `<blockquote class="twitter-tweet"><a href="${match}"></a></blockquote><script async src="https://platform.twitter.com/widgets.js" charset="utf-8" />`;
    });

    return content;
}