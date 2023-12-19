import slugify from 'slugify';
import {computed} from "vue/dist/vue";

export const lowerSlugify = (text) => {
    return slugify(text, {lower: true});
}


export const stripHtmlTags = (str) => {
    if ((str === null) || (str === ''))
        return '';
    else
        str = str.toString();

    return str.replace(/(<([^>]+)>)/ig, '');
}

export const alignmentClassGenerator = (alignment) => {
    if (alignment === 'center') {
        return 'text-center justify-center'
    } else if(alignment === 'right'){
        return 'text-right text-end justify-end items-end'
    } else{
        return 'text-left justify-start items-start'
    }
}