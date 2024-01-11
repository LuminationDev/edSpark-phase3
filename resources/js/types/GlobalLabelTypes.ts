export type LabelSelectorItem = {
    label_id: number,
    label_value: string,
    label_description: string,
    label_type: 'year' | 'learning' | 'capability' | 'category'
}

export type GroupedLabel = {
    [key in LabelSelectorItem['type']]: {
        id: number;
        value: string;
        name: string;
    }[];
};