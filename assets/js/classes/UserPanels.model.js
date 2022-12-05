class UserPanels {
    fadeInItem (item, timer) {
        let fadeInDuration = timer;

        let intervalId = setInterval(() => {
            let itemOpacity = Number(window.getComputedStyle(item).getPropertyValue("opacity"));
            if (itemOpacity < 1) {
                item.style.opacity = itemOpacity + .1;
            }
            else {
                clearInterval(intervalId);
            }
        }, fadeInDuration/100)
    }

    fadeOutItem (item, timer) {
        let fadeInDuration = timer;

        let intervalId = setInterval(() => {
            let itemOpacity = Number(window.getComputedStyle(item).getPropertyValue("opacity"));
            if (itemOpacity > 0) {
                item.style.opacity = itemOpacity - .1;
            }
            else {
                clearInterval(intervalId);
            }
        }, fadeInDuration/100)
    }
}