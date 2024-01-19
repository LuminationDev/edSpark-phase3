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
}


export enum CatalogueFilterField{
    Type = 'type',
    Brand = 'brand',
    Vendor = 'vendor',
    Category = 'category',
}