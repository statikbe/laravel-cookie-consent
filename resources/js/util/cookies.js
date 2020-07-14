export function getCookie(key) {

    if (!key) return null;

    return (
        decodeURIComponent(
            document.cookie.replace(
                new RegExp(
                    '(?:(?:^|.*;)\\s*' +
                    encodeURIComponent(key).replace(/[\-\.\+\*]/g, '\\$&') +
                    '\\s*\\=\\s*([^;]*).*$)|^.*$'
                ),
                '$1'
            )
        ) || null
    );
}

export function setCookie(key, expireDays, value) {

    const date = new Date();
    let expires = null;

    if (expireDays) {

        date.setTime(date.getTime() + expireDays * 24 * 60 * 60 * 1000);

        expires = date.toUTCString();
    }

    document.cookie = `${encodeURIComponent(key)}=${encodeURIComponent(value)}${expires ? `; expires=${expires}` : ''}; path=/`;
}