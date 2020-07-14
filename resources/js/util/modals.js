
import {
    isHidden,
    showElement,
    hideElement,
    getSiblings,
} from './dom';

export function showModal(modal, ignoreBackdrop = false) {

    //  Make all sibling elements inert (not focusable)
    getSiblings(modal, ':not(.js-lcc-backdrop)').forEach((sibling) => {
        sibling.inert = true;
    });

    modal.inert = false;

    if (!ignoreBackdrop) {
        fadeBackdrop();
    }

    showElement(modal);
}

export function hideModal(modal, ignoreBackdrop = false) {

    getSiblings(modal, ':not(.js-lcc-modal)').forEach((sibling) => {
        sibling.inert = false;
    });

    modal.inert = true;

    hideElement(modal);

    if (!ignoreBackdrop) {
        fadeBackdrop();
    }
}

function fadeBackdrop() {

    const backdropElement = document.querySelector('.js-lcc-backdrop');

    if (isHidden(backdropElement)) {
        showElement(backdropElement);
        backdropElement.style.opacity = '1';
    } else {

        backdropElement.addEventListener('transitionend', hideBackdrop);
        backdropElement.style.opacity = '0';
    }

    function hideBackdrop() {

        hideElement(backdropElement);

        backdropElement.removeEventListener('transitionend', hideBackdrop);
    };
}