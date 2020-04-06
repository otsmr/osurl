function copyToClipboard(text, e) {
    if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
        const textarea = document.createElement("textarea");
        textarea.textContent = text;
        textarea.style.position = "fixed";
        document.body.appendChild(textarea);
        textarea.select();
        try {
            e.className = "link copyed";
            setTimeout(() => {
                e.className = "link";
            }, 100);
            return document.execCommand("copy");
        } catch (ex) {
            return false;
        } finally {
            document.body.removeChild(textarea);
        }
    }
}