export const serverURL = import.meta.env.VITE_SERVER_URL_API
console.log("SERVER", serverURL);
export const imageURL = import.meta.env.VITE_SERVER_IMAGE_API

export const likeURL = `${serverURL}/like`

export const bookmarkURL = `${serverURL}/bookmark`
