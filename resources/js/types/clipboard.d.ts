interface Clipboard {
    readText(): Promise<string>;
    writeText(data: string): Promise<void>;
}

interface Navigator {
    clipboard: Clipboard;
}
