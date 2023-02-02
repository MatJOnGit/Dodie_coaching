class ElementFader {
    /*********************************************************
    Adds a progressive fade in effect to the item in parameter
    *********************************************************/
    fadeInItem (item, timer, maxOpacity) {
        let intervalId = setInterval(() => {
            let itemOpacity = Number(window.getComputedStyle(item).getPropertyValue('opacity'));

            if (itemOpacity < maxOpacity) {
                item.style.opacity = itemOpacity + .1;
            }

            else {
                clearInterval(intervalId);
            }

        }, timer/100)
    }
    
    /**********************************************************
    Adds a progressive fade out effect to the item in parameter
    **********************************************************/
    fadeOutItem (item, timer) {
        let intervalId = setInterval(() => {
            let itemOpacity = Number(window.getComputedStyle(item).getPropertyValue('opacity'));

            if (itemOpacity > 0) {
                item.style.opacity = itemOpacity - .1;
            }

            else {
                clearInterval(intervalId);
            }

        }, timer/100)
    }
}