export const formatDateToTimeOnly = (date) =>{
    return new Date(Date.parse(date)).toLocaleString('en-US',{ hour: 'numeric', minute: 'numeric', hour12: true } )
}
export const formatDateToDayTime = (date) =>{
    return new Date(Date.parse(date)).toLocaleString('en-US',{ weekday: 'long',day: '2-digit', month:'short' ,hour: 'numeric', minute: 'numeric', hour12: true } )
}
