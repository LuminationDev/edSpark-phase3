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

export const safelyExtractFirstObjectFromArray = (array) =>{
    if(array.length > 1){
        throw Error('Array has more than 1 object')
    }
    else{
        return array[0]
    }
}

export const isEmptyFunction = (func):boolean =>{
    const functionAsString = func.toString();

    // Remove whitespace and check if it matches the pattern of an empty function
    return /^(function\s?\(\)\s?\{\s?\}|^\(\s?\)\s?=>\s?\{\s?\})$/.test(functionAsString);
}