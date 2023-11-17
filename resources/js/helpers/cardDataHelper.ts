import {appURL} from "@/js/constants/serverUrl";
import {guid} from "@/js/helpers/guidGenerator";
import {lowerSlugify} from "@/js/helpers/stringHelpers";

export const cardDataHelper = (cardData, section) => {
    switch (section) {
        case 'events':
            return cardData.map(data => {
                return {
                    id: data.event_id,
                    title: data.event_title,
                    excerpt: data.event_excerpt,
                    author: data.author,
                    created_at: data.start_date,
                    start_date: data.start_date,
                    cover_image: data.cover_image,
                    type: data.event_type,
                    guid: guid(),
                }
            })
        case 'advice':
            return cardData.map(data => {
                return {
                    id: data.post_id,
                    title: data.post_title,
                    excerpt: data.post_excerpt,
                    author: data.author,
                    created_at: data.created_at,
                    cover_image: data.cover_image,
                    type: data.advice_type[0],
                    guid: guid(),

                }
            })
        case 'software':
            return cardData.map(data => {
                return {
                    id: data.post_id,
                    title: data.post_title,
                    excerpt: data.post_excerpt,
                    author: data.author,
                    created_at: data.created_at,
                    cover_image: data.cover_image,
                    type: data.software_type[0],
                    guid: guid(),
                }
            });
        case 'schools':
            return cardData.map(data => {
                return {
                    id: data.id,
                    title: data.name,
                    tech_used: data.tech_used,
                    cover_image: data.cover_image,
                    guid: guid(),
                }
            })
        default:
            break;
    }
}
/*
 * Takes an array of object and add guid field to each object
 * @params Array of Objects
 * @returns Array of object with each array item appended with guid()
 */
export const  cardDataWithGuid = (cardData) => {
    return cardData.map(data =>{
        return {...data, guid: guid()}
    })
}

export const cardLinkGenerator = (type: string, name:string ,id?: number ) => {
    if(type.toLowerCase() === 'school'){
        return `${appURL}/schools/${encodeURIComponent(name)}`
    } else{
        return `${appURL}/${type}/resources/${id}/${lowerSlugify(name)}`
    }
}