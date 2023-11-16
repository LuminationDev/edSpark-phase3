import slugify from 'slugify';

export const lowerSlugify = (text) => {
    if (!text || (text === '')) return '';
    else return slugify(text, {lower: true});
}


export const stripHtmlTags = (str) => {
    if ((str === null) || (str === ''))
        return '';
    else
        str = str.toString();

    return str.replace(/(<([^>]+)>)/ig, '');
}