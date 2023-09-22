export const getPositionAtCenter = (element) : {x: number, y: number} => {
    if (element) {
        const {top, left, width, height} = element.getBoundingClientRect();
        return {
            x: left + width / 2,
            y: top + height / 2
        };
    } else {
        return {
            x: 0,
            y: 0
        }
    }
}

export const getDistanceBetweenElements = (a, b) : number => {
    const aPosition = getPositionAtCenter(a);
    const bPosition = getPositionAtCenter(b);

    return Math.hypot(aPosition.x - bPosition.x, aPosition.y - bPosition.y);
}