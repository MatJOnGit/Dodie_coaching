class DynamicMenuDisplayer extends Fader {
    constructor() {
        super();
        
        this._menuTriggerBtn = document.getElementById('dynamic-menu-button');
        this._bodyElt = document.getElementsByTagName('body')[0];
        
        this._dynamicMenuContainer;
        this._closeDynamicMenuLayer;
        this._dynamicMenu;
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
            this.setDynamicLayer();
            this.setCloseMenuLayer();
            this.triggerCorrectMenuButtons();
            this.addCloseMenuEltsListener();
        })
    }
    
    init() {
        this.addMenuTriggerBtnListener();
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
        
        this.closeDynamicMenuLayer = dynamicMenuLayer.appendChild(closeDynamicMenuLayer);
        this.dynamicMenu = dynamicMenuLayer.appendChild(dynamicMenu);
        dynamicMenuContainer.appendChild(blurryLayer);
        dynamicMenuContainer.appendChild(dynamicMenuLayer);
        this.dynamicMenuContainer = this.bodyElt.appendChild(dynamicMenuContainer);
        
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
            
        }, timer/1000);
    }
    
    triggerCorrectMenuButtons() {
        document.getElementById('logged') ? this.addLoggedUserMenu() : this.addDefaultMenu();
    }
}