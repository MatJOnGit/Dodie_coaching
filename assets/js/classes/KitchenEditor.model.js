class KitchenEditor {
    constructor(apiKey, itemId) {
        this._adminPanel = document.getElementsByClassName('admin-panel')[0];
        this._apiKey = apiKey;
        this._itemId = itemId;
        this._onlyNumbersRegex = /^\d+$/;
    }
    
    get adminPanel() {
        return this._adminPanel;
    }
    
    get apiKey() {
        return this._apiKey;
    }
    
    get itemId() {
        return this._itemId;
    }
    
    get onlyNumbersRegex() {
        return this._onlyNumbersRegex;
    }
    
    set itemId(itemId) {
        this._itemId = itemId;
    }
    
    clearPanel() {
        this.adminPanel.innerHTML = '';
    }
    
    scrollTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
}