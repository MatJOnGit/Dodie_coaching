class Fader {
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