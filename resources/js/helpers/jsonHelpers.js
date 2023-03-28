export const parseToJsonIfString = (data) => {
    return (typeof data === 'string') ? JSON.parse(data) : data
}