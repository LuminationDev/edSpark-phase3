import slugify from 'slugify';

const lowerSlugify = (text) => {
    return slugify(text, { lower: true });
}

export default lowerSlugify;