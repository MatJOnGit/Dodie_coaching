class DynamicMenu extends UserPanels {
    constructor() {
        super();

        this._menuTriggerBtn = document.getElementById('dynamic-menu-button');
        this._showcasePanel = document.getElementsByClassName('showcase-panel')[0];
    }

    get menuTriggerBtn() {
        return this._menuTriggerBtn;
    }

    get showcasePanel() {
        return this._showcasePanel;
    }

    init() {
        this.addMenuTriggerBtnListener();
    }

    addMenuTriggerBtnListener() {
        this.menuTriggerBtn.addEventListener('click', () => {
            this.setDynamicLayer();
            this.setCloseMenuLayer();
            this.triggerCorrectMenuButtons();
            this.addCloseMenuEltsListener();
        })
    }

    setDynamicLayer() {
        const blurryLayer = document.createElement('div');
        const dynamicMenuLayer = document.createElement('div');
        const closeDynamicMenuLayer = document.createElement('div');
        const dynamicMenu = document.createElement('div');

        blurryLayer.classList.add('blurry-layer');
        blurryLayer.style.opacity = 0;
        dynamicMenuLayer.classList.add('dynamic-menu-layer', 'blue-bkgd');
        closeDynamicMenuLayer.classList.add('close-menu-layer');
        dynamicMenu.classList.add('dynamic-menu');

        this.showcasePanel.appendChild(blurryLayer);
        dynamicMenuLayer.appendChild(closeDynamicMenuLayer);
        dynamicMenuLayer.appendChild(dynamicMenu);
        this.showcasePanel.appendChild(dynamicMenuLayer);

        this.fadeInItem(blurryLayer, 3000, 0.8);
        this.slideInItem(dynamicMenuLayer, 2000);
    }

    slideInItem(item, timer) {
        let intervalId = setInterval(() => {
            let itemWidth = item.getBoundingClientRect().width;
            if (item.getBoundingClientRect().width < 225) {
                item.style.width = (itemWidth + 5 + 'px');
            }
            else {
                clearInterval(intervalId);
            }
        }, timer/1000)
    }

    setCloseMenuLayer() {
        const closeMenuLayer = document.getElementsByClassName('close-menu-layer')[0];
        
        const closeMenuBtn = document.createElement('button');
        const closeMenuIcon = document.createElement('i');
        
        closeMenuIcon.classList.add('fa-solid', 'fa-xmark');
        closeMenuIcon.id = 'close-menu-button';
        
        closeMenuBtn.appendChild(closeMenuIcon);
        closeMenuLayer.appendChild(closeMenuBtn);
    }

    triggerCorrectMenuButtons() {
        if (document.getElementById('logged')) {
            this.addLoggedUserMenu();
        }

        else {
            this.addDefaultMenu();
        }
    }

    addCloseMenuEltsListener() {
        const dynamicMenu = document.getElementsByClassName('dynamic-menu-layer')[0];
        const closeMenuBtn = document.getElementById('close-menu-button');
        const blurryLayer = document.getElementsByClassName('blurry-layer')[0];
        const clickableElts = [closeMenuBtn, blurryLayer];

        clickableElts.forEach((clickedElt) => {
            clickedElt.addEventListener('click', () => {
                this.fadeOutItem(dynamicMenu, 1000);
    
                setTimeout(() => {
                    this.showcasePanel.removeChild(dynamicMenu);
                    this.showcasePanel.removeChild(blurryLayer);
                }, 100)
            })
        })
    }

    addLoggedUserMenu() {
        const dynamicMenu = document.getElementsByClassName('dynamic-menu')[0];
        const linkListElt = document.createElement('ul');
        const dashboardListItem = document.createElement('li');
        const contactListItem = document.createElement('li');
        const logoutListItem = document.createElement('li');
        const dashboardLink = document.createElement('a');
        const contactLink = document.createElement('a');
        const logoutLink = document.createElement('a');
        const dashboardSpan = document.createElement('span');
        const contactSpan = document.createElement('span');
        const logoutSpan = document.createElement('span');

        
        dashboardListItem.classList.add('dynamic-menu-item');
        contactListItem.classList.add('dynamic-menu-item');
        logoutListItem.classList.add('dynamic-menu-item');
        dashboardLink.textContent = 'Tableau de bord';
        dashboardLink.href = 'index.php?page=dashboard';
        contactLink.textContent = 'Contact';
        contactLink.href = 'index.php';
        logoutLink.textContent = 'Déconnexion';
        logoutLink.href = 'index.php?action=logout';
        dashboardSpan.classList.add('menu-splitter');
        contactSpan.classList.add('menu-splitter');
        logoutSpan.classList.add('menu-splitter');
        
        dashboardListItem.appendChild(dashboardLink);
        contactListItem.appendChild(contactLink);
        logoutListItem.appendChild(logoutLink);
        
        linkListElt.appendChild(dashboardListItem);
        linkListElt.appendChild(dashboardSpan);
        linkListElt.appendChild(contactListItem);
        linkListElt.appendChild(contactSpan);
        linkListElt.appendChild(logoutListItem);
        linkListElt.appendChild(logoutSpan);
        
        dynamicMenu.appendChild(linkListElt)
    }

    addDefaultMenu() {
        const dynamicMenu = document.getElementsByClassName('dynamic-menu')[0];
        const linkListElt = document.createElement('ul');
        const loginListItem = document.createElement('li');
        const contactListItem = document.createElement('li');
        const loginLink = document.createElement('a');
        const contactLink = document.createElement('a');
        const loginSpan = document.createElement('span');
        const contactSpan = document.createElement('span');

        loginLink.textContent = 'Connexion';
        loginLink.href = 'index.php?page=login';
        contactLink.textContent = 'Contact';
        contactLink.href = 'index.php';
        loginSpan.classList.add('menu-splitter');
        contactSpan.classList.add('menu-splitter');
        
        loginListItem.appendChild(loginLink);
        contactListItem.appendChild(contactLink);
        
        linkListElt.appendChild(loginListItem);
        linkListElt.appendChild(loginSpan);
        linkListElt.appendChild(contactListItem);
        linkListElt.appendChild(contactSpan);
        
        dynamicMenu.appendChild(linkListElt)
    }
}