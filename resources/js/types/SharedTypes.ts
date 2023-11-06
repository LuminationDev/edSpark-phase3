export type ExtraContentFilamentType = {
    data: {
        template: string;
        extra_content: {
            date_items?: {
                item: {
                    content: string;
                    heading: string;
                    start_date: string;
                }[];
            };
        };
    };
    type: string;
};



export type SWRVResponse<T> = {
    data: T;
    error: any;
};


