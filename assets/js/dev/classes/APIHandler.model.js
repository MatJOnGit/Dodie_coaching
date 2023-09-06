class APIHandler {
    constructor(apiKey) {
        this._apiKey = apiKey;
        
        this._headers = {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${this.apiKey}`
        }
    }
    
    get apiKey() {
        return this._apiKey;
    }
    
    get headers() {
        return this._headers
    }
    
    async sendRequest(endpoint, method, body = null) {
        const options = {
            method: method,
            headers: this.headers,
        };
        
        if (body !== null) {
            options.body = JSON.stringify(body);
        }
        
        const response = await fetch(endpoint, options);
        const data = await response.json();
        return data;
    }
}