export type LabelSelectorItem = {
    id: number,
    value: string,
    description: string,
    type: 'year' | 'learning' | 'capability' | 'category'
}

export type GroupedLabel = {
    [key in LabelSelectorItem['type']]: {
        id: number;
        value: string;
        name: string;
    }[];
};