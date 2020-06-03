export function showElement(element) {
    element.style.display = '';
}

export function hideElement(element) {
    element.style.display = 'none';
}

export function isHidden(element) {
    return element.style.display === 'none';
}

export function elementMatchesSelector(element, selector) {

    if (typeof element.matches === 'function') {
        return element.matches(selector);
    }

    const propertySuffix = 'MatchesSelector';

    //  Fallback for older browsers that don't support Element.matches(), but do support prefixed functions
    for (let property in element) {
        if (property.substring(property.length - propertySuffix.length, property.length) === propertySuffix) {
            return element[property](selector);
        }
    }

    return false;
}

export function getSiblings(element, selector) {

	const siblings = [];
	let sibling = element.parentNode.firstChild;

	while (sibling) {

		if (sibling.nodeType === 1 && sibling !== element) {
            if (typeof selector !== 'undefined') {
                if (elementMatchesSelector(sibling, selector)) {
                    siblings.push(sibling);
                }
            } else {
                siblings.push(sibling);
            }
        }
        
		sibling = sibling.nextSibling
    }

	return siblings;
}

export function addEventListener(eventType, selector, listener) {

    [].forEach.call(document.querySelectorAll(selector), function (element) {
        element.addEventListener(eventType, listener);
    });
}