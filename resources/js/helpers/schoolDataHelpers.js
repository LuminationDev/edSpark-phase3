import {
    defaultSchoolContent,
    defaultSchoolPedagogicalApproaches, defaultSchoolTechLandscape,
    defaultSchoolTechList
} from "@/js/constants/schoolContentDefault";

/**
 *
 * @param schoolData
 * School data as object containing {
 *     site_id: number
 *     owner_id: number
 *     name: string
 *     content_blocks: Object
 *     logo: file | string
 *     cover_image: file | string
 *     tech_used : array<Object>
 *     pedagogical_approaches: Object
 *     tech_landscape: Object
 * }
 * @return FormData()
 */
export const schoolDataFormDataBuilder = (schoolData) =>{
    let schoolFormData = new FormData()
    schoolFormData.append('id',schoolData.id)
    schoolFormData.append('site_id',schoolData.site.site_id)
    schoolFormData.append('owner_id', schoolData.owner.owner_id)
    schoolFormData.append('name', schoolData.name)

    if(schoolData.content_blocks){
        schoolFormData.append('content_blocks', JSON.stringify(schoolData.content_blocks))
    } else {
        schoolFormData.append('content_blocks', JSON.stringify(defaultSchoolContent))
    }

    if(schoolData.tech_used){
        schoolFormData.append('tech_used', JSON.stringify(schoolData.tech_used))
    }else{
        schoolFormData.append('tech_used', JSON.stringify(defaultSchoolTechList))
    }
    if(schoolData.pedagogical_approaches){
        schoolFormData.append('pedagogical_approaches', JSON.stringify(schoolData.pedagogical_approaches))
    }else {
        schoolFormData.append('pedagogical_approaches', JSON.stringify(defaultSchoolPedagogicalApproaches))

    }
    if(schoolData.tech_landscape){
        schoolFormData.append('tech_landscape', JSON.stringify(schoolData.tech_landscape))
    } else {
        schoolFormData.append('tech_landscape', JSON.stringify(defaultSchoolTechLandscape))
    }
    return schoolFormData
}

export const printOutFormData = (inputFormData) => {
    for (const pair of inputFormData.entries()){
        console.log(`${pair[0]}, ${pair[1]}`)
    }
}