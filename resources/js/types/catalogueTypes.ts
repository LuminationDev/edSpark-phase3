export interface CatalogueItemType {
    id: number;
    unique_reference: string;
    type: string;
    brand: string;
    name: string;
    vendor: string;
    category: string;
    price_inc_gst: string;
    processor: string;
    storage: string;
    memory: string;
    form_factor: string;
    display: string;
    graphics: string | null;
    wireless: "No" | "Yes";
    webcam: "No" | "Yes";
    operating_system: string;
    warranty: string;
    battery_life: string | null;
    weight: string | null;
    stylus: "No" | "Yes" | null;
    other: string | null;
    available_now: "No" | "Yes";
    corporate: "No" | "Yes";
    administration: "No" | "Yes";
    curriculum: "No" | "Yes";
    image: string;
    product_number: string | null;
    price_expiry: string | null;
    cover_image: {
        uuid: string;
        extension: string;
    },
    quantity?: number;
}

export interface CatalogueGroupedItemType {
    name: string,
    value: string,
    display_text: string,
    group: string
}

export enum CatalogueFilterField {
    All = 'all',
    Type = 'type',
    Brand = 'brand',
    Vendor = 'vendor',
    Category = 'category',
    Processor = 'processor',
    Memory = 'memory',
    Storage = 'storage',

}

export const catalogueComparisonHeaders = ['overview', 'specs']
export const catalogueSingleHeaders = ['overview', 'specs']
// export const catalogueSingleHeaders = ['overview', 'specs', 'hardware', 'availability', 'more_info']
