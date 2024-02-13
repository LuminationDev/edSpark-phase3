/**
 * returns the description for a domain given its name
 * @param domainName
 * @returns {string}
 */
export function useDomainDescription(domainName) {
    switch(domainName) {
        case 'teaching':
            return "Exploring innovative teaching techniques and effective student engagement.";
        case 'learning':
            return "Exploring innovative learning techniques and effective student engagement.";
        case 'leading':
            return "Exploring innovative leading techniques and effective student engagement.";
        case 'managing':
            return "Exploring innovative managing techniques and effective student engagement.";
        default:
            return '';
    }
}
