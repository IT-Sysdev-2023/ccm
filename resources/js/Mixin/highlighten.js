export function highlighten() {

    const highlightText = (textVar, searchQuery) => {
        if (!searchQuery) return textVar;

        if (textVar !== null) {
            const text = textVar?.toString();
            const regex = new RegExp(searchQuery, "gi");
            return text?.replace(
                regex,
                (match) =>
                    `<span style="background-color: yellow">${match}</span>`
            );
        }
    };

    return { highlightText };
}
