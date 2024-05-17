import slugify from 'slugify';

export const lowerSlugify = (text) => {
    if(!text){
        return ''
    } else {
        return slugify(text, {lower: true});

    }
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

export const simpleValidateUrl = (url: string) : boolean =>{
    const validUrlRegex = /^(https?:\/\/)?(www\.)?([-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*))$/;
    return validUrlRegex.test(url)
}
export const convertStringValuesToIntRecursive = (obj) => {
    if (typeof obj === 'object') {
        if (Array.isArray(obj)) {
            obj.forEach((value, index) => {
                obj[index] = convertStringValuesToIntRecursive(value);
            });
        } else {
            for (const key in obj) {
                obj[key] = convertStringValuesToIntRecursive(obj[key]);
            }
        }
    } else if (typeof obj === 'string' && /^-?\d+$/.test(obj)) {
        obj = parseInt(obj);
    }
    return obj;
}
