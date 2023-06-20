

export const cardDataHelper = (cardData, section) => {
    console.log(section, cardData);
    let cardObject = {};

    const tempArray = [];

    switch (section) {
        case 'events':
                cardData.forEach(data => {
                    tempArray.push({
                        id: data.event_id,
                        title: data.event_title,
                        excerpt: data.event_excerpt,
                        author: data.author,
                        created_at: data.created_at,
                        cover_image: data.cover_image,
                        type: data.event_type
                    })
                })

            break;
        case 'advice':
                let count = 0;
                cardData.forEach(data => {
                    count++
                    console.log(count, data.advice_type);

                    tempArray.push({
                        id: data.post_id,
                        title: data.post_title,
                        excerpt: data.post_excerpt,
                        author: data.author,
                        created_at: data.created_at,
                        cover_image: data.cover_image,
                        type: data.advice_type[0]
                    })
                })
            break;
        case 'software':
                cardData.forEach(data => {
                    tempArray.push({
                        id: data.post_id,
                        title: data.post_title,
                        excerpt: data.post_excerpt,
                        author: data.author,
                        created_at: data.created_at,
                        cover_image: data.cover_image,
                        type: data.software_type[0]
                    })
                });
            break;
        case 'schools':
                cardData.forEach(data => {
                    tempArray.push({
                        id: data.id,
                        title: data.name,
                        tech_used: data.tech_used,
                        cover_image: data.cover_image
                    });
                });
            break;

        default:
            break;
    }

    return tempArray;

}
