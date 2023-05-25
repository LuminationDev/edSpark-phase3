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


//
// {
//     "Graphics": "RTX 4090Ti",
//     "Processor": "Intel i7 - 13950KX",
//     "Screen Size": "14\"",
//     "Memory capacity": "64GB",
//     "Operating System": "Windows 11",
//     "Storage capacity": "16TB",
//     "Feature highlights (separate each feature with a comma)": "Most powerful laptop in the world, Passive Cooling, ultra compatibility, 5 mins charge for 12 hours of heavy use"
// }