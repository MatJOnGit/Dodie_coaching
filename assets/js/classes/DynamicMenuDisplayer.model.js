class DynamicMenuDisplayer extends ElementFader {
    constructor() {
        super();
        
        this._menuTriggerBtn = document.getElementById('dynamic-menu-button');
        this._bodyElt = document.getElementsByTagName('body')[0];
        
        this._dynamicMenuWidthPerDevice = {
            'mobile': 225,
            'tablet': 500,
            'others': 700
        }
    }

    get dynamicMenuWidth() {
        return this._dynamicMenuWidth;
    }

    get dynamicMenuWidthPerDevice() {
        return this._dynamicMenuWidthPerDevice;
    }
    
    get bodyElt() {
        return this._bodyElt;
    }
    
    get closeDynamicMenuLayer() {
        return this._closeDynamicMenuLayer;
    }
    
    get dynamicMenu() {
        return this._dynamicMenu;
    }
    
    get dynamicMenuContainer() {
        return this._dynamicMenuContainer;
    }
    
    get menuTriggerBtn() {
        return this._menuTriggerBtn;
    }
    
    set closeDynamicMenuLayer(item) {
        this._closeDynamicMenuLayer = item;
    }
    
    set dynamicMenu(item) {
        this._dynamicMenu = item;
    }
    
    set dynamicMenuContainer(item) {
        this._dynamicMenuContainer = item;
    }

    set dynamicMenuWidth(width) {
        this._dynamicMenuWidth = width;
    }

    setDynamicMenuWidth() {
        const windowScreenSize = window.innerWidth;
        
        if (windowScreenSize < 768) {
            this.dynamicMenuWidth = this.dynamicMenuWidthPerDevice.mobile
        }
        else if (windowScreenSize < 1024) {
            this.dynamicMenuWidth = this.dynamicMenuWidthPerDevice.tablet
        }
        else {
            this.dynamicMenuWidth = this.dynamicMenuWidthPerDevice.others
        }
    }
    
    addCloseMenuEltsListener() {
        const closeMenuBtn = document.getElementById('close-menu-button');
        const blurryLayer = document.getElementsByClassName('blurry-layer')[0];
        const clickableElts = [closeMenuBtn, blurryLayer];
        
        clickableElts.forEach((clickedElt) => {
            clickedElt.addEventListener('click', () => {
                this.fadeOutItem(this.dynamicMenuContainer, 1000);
                
                setTimeout(() => {
                    this.bodyElt.removeChild(this.dynamicMenuContainer);
                }, 100);
            });
        });
    }
    
    addDefaultMenu() {
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
        this.dynamicMenu.appendChild(linkListElt);
    }
    
    addLoggedUserMenu() {
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
        this.dynamicMenu.appendChild(linkListElt);
    }
    
    addMenuTriggerBtnListener() {
        this.menuTriggerBtn.addEventListener('click', () => {
            this.setDynamicMenuWidth();
            this.setDynamicLayer();
            this.setCloseMenuLayer();
            this.triggerCorrectMenuButtons();
            this.addCloseMenuEltsListener();
        });
    }
    
    setCloseMenuLayer() {
        const closeMenuBtn = document.createElement('button');
        const closeMenuIcon = document.createElement('i');
        
        closeMenuIcon.classList.add('fa-solid', 'fa-xmark');
        closeMenuIcon.id = 'close-menu-button';
        
        closeMenuBtn.appendChild(closeMenuIcon);
        this.closeDynamicMenuLayer.appendChild(closeMenuBtn);
    }
    
    setDynamicLayer() {
        const animationDuration = 200;
        const dynamicMenuContainer = document.createElement('div');
        const blurryLayer = document.createElement('div');
        const dynamicMenuLayer = document.createElement('div');
        const closeDynamicMenuLayer = document.createElement('div');
        const dynamicMenu = document.createElement('div');
        
        dynamicMenuContainer.id = 'dynamic-menu-container';
        blurryLayer.classList.add('blurry-layer');
        blurryLayer.style.opacity = 0;
        dynamicMenuLayer.classList.add('dynamic-menu-layer', 'blue-bkgd');
        closeDynamicMenuLayer.classList.add('close-menu-layer');
        dynamicMenu.classList.add('dynamic-menu');
        
        this.dynamicMenu = dynamicMenuLayer.appendChild(dynamicMenu);
        dynamicMenuContainer.appendChild(blurryLayer);
        dynamicMenuContainer.appendChild(dynamicMenuLayer);
        this.dynamicMenuContainer = this.bodyElt.appendChild(dynamicMenuContainer);
        
        this.fadeInItem(blurryLayer, 3000, 0.8);
        this.slideInMenu(dynamicMenuLayer, animationDuration);
        
        this.closeDynamicMenuLayer = dynamicMenuLayer.appendChild(closeDynamicMenuLayer);
    }
    
    slideInMenu(item, duration) {
        const stepCount = Math.round(duration / 16);
        const increment = this.dynamicMenuWidth / stepCount;
        
        let step = 0;
        
        let intervalId = setInterval(() => {
            if (step >= stepCount) {
                clearInterval(intervalId);
            }
            
            else {
                const itemWidth = item.getBoundingClientRect().width;
                const newWidth = Math.min(itemWidth + increment, this.dynamicMenuWidth);
                item.style.width = newWidth + 'px';
                step++;
            }
        }, 16);
    }
    
    triggerCorrectMenuButtons() {
        document.getElementById('logged') ? this.addLoggedUserMenu() : this.addDefaultMenu();
    }
}