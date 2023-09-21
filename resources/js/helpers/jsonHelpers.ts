export const parseToJsonIfString = (data) => {
    return (typeof data === 'string') ? JSON.parse(data) : data
}

/**
 * Can be extended in the future
 * @param data array of object with content_blocks and tech_used
 */
export const schoolContentArrParser = data => {
    const copyOfData = _.cloneDeep(data)
    for (let i = 0; i < copyOfData.length; i++) {
        copyOfData[i].content_blocks = parseToJsonIfString(copyOfData[i].content_blocks)
        copyOfData[i].tech_used = parseToJsonIfString(copyOfData[i].tech_used)
    }
    return copyOfData
}


export const intersectObjects = (obj1, obj2): object => {
    const commonKeys = Object.keys(obj1).filter(key => key in obj2);
    const intersection = {};
    commonKeys.forEach(key => {
        intersection[key] = obj1[key];
    });
    return intersection;
}

export const differenceObjects = (obj1, obj2): object =>  {
    const uniqueKeys = Object.keys(obj1).filter(key => !(key in obj2));
    const difference = {};
    uniqueKeys.forEach(key => {
        difference[key] = obj1[key];
    });
    return difference;
}