export const parseToJsonIfString = (data) => {
    return (typeof data === 'string') ? JSON.parse(data) : data
}

/**
 * Can be extended in the future
 * @param data array of object with content_blocks and tech_used
 */
export const schoolContentArrParser = data => {
    const copyOfData = _.cloneDeep(data)
    for(let i = 0 ; i < copyOfData.length; i++){
        copyOfData[i].content_blocks = parseToJsonIfString(copyOfData[i].content_blocks)
        copyOfData[i].tech_used = parseToJsonIfString(copyOfData[i].tech_used)
    }
    return copyOfData
}