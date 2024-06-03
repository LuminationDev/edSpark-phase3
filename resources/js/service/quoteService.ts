import axios from "axios";

import {API_ENDPOINTS} from "@/js/constants/API_ENDPOINTS";
import {serverURL} from "@/js/constants/serverUrl";

export const quoteService = {
    getCart: async () => {
        return axios.get(`${API_ENDPOINTS.CART.GET_CART}`).then(res => {
            return res.data
        }).catch(err => {
            throw new Error('Failed to get cart' + err.message)
        })
    },
    addItemToCart: async (itemRef: string, quantity: number) => {
        const data = {
            unique_reference: itemRef,
            quantity: quantity
        }
        return axios.post(`${API_ENDPOINTS.CART.ADD_ITEM_TO_CART}`, data).then(res => {
            return res.data
        }).catch(err => {
            throw new Error('Failed to add item to cart' + err.message)
        })
    },
    updateItemQuantityInCart: async (itemRef, quantity) => {
        const updatePutPayload = {
            quantity: quantity
        }
        return axios.put(`${API_ENDPOINTS.CART.UPDATE_QUANTITY_CART}/${itemRef}/update`, updatePutPayload).then(res => {
            return res.data
        }).catch(err => {
            throw new Error('Failed to update item quantity' + err.message)
        })
    },
    deleteItemInCart: async (itemRef) => {
        return axios.delete(`${API_ENDPOINTS.CART.DELETE_ITEM_CART}/${itemRef}`).then(res => {
            return res.data
        }).catch(err => {
            throw new Error('Failed to delete item from cart' + err.message)
        })
    },
    clearCart: async () => {
        return axios.delete(`${API_ENDPOINTS.CART.CLEAR_CART}`).then(res => {
            return res.data
        })
    },
    checkoutCart: async (vendor) => {
        const body = {
            vendor: vendor
        }
        return axios.post(`${API_ENDPOINTS.CART.CHECKOUT_CART}`, body).then(res => {
            return res.data
        })
    },
    getGenQuote: async () => {
        return axios.get(API_ENDPOINTS.QUOTE.GET_QUOTE).then(res => res.data.quotes)
    },
    getVendorData: async (vendorName) => {
        return axios.get(`${API_ENDPOINTS.QUOTE.GET_VENDOR}${vendorName}`).then(res => res.data.vendor)
    },

    printQuote: async () => {
        const elementId = '#quote-template-print'; // Replace with your element's ID
        const elements = document.querySelectorAll(elementId);

        let allPageContents = '';

        for (const element of elements) {
            if (!element) {
                console.error(`Element with ID ${elementId} not found.`);
                return;
            }

            // Clone the element to preserve the original state
            const clonedElement = element.cloneNode(true);
            clonedElement.classList.remove('hidden');

            // Function to fetch the image and convert it to base64
            const getImageBase64 = async (img) => {
                const imgUrl = img.src;
                const response = await fetch(imgUrl);
                const blob = await response.blob();
                return new Promise((resolve, reject) => {
                    const reader = new FileReader();
                    reader.onloadend = () => resolve(reader.result);
                    reader.onerror = reject;
                    reader.readAsDataURL(blob);
                });
            };

            // Get all images in the cloned element
            const images = clonedElement.getElementsByTagName('img');
            for (const img of images) {
                const base64Data = await getImageBase64(img);
                img.src = base64Data;
            }

            const getAllStyles = () => {
                return Array.from(document.styleSheets).map(styleSheet => {
                    try {
                        return Array.from(styleSheet.cssRules).map(rule => {
                            return rule.cssText.trim();
                        }).join(' ');
                    } catch (e) {
                        // Handle the SecurityError for cross-origin stylesheets
                        console.warn(`Could not access stylesheet: ${styleSheet.href}`);
                        return '';
                    }
                }).join(' ');
            };

            const allStyles = getAllStyles();

            const styleElement = document.createElement('style');
            styleElement.textContent = allStyles;
            clonedElement.appendChild(styleElement);

            // Serialize the cloned element to HTML
            const pageContent = clonedElement.innerHTML;

            // Add page break if not the last element
            if (element !== elements[elements.length - 1]) {
                allPageContents += pageContent + '<div style="page-break-after: always;"></div>';
            } else {
                allPageContents += pageContent;
            }
        }

        const payload = {html: JSON.stringify({html: allPageContents})};
        console.log(payload);

        return axios.post('http://localhost:8000/api/quote/generate-pdf', payload, {responseType: 'blob'})
            .then(response => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = 'page.pdf';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(error => console.error('Error:', error));
    }
}