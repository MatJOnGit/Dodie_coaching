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
    
    async sendGetRequest(endpoint) {
        try {
            const response = await fetch(endpoint, {
                method: 'GET',
                headers: this.headers
            });
            
            return response.json();
        }
        
        catch(error) {
            console.error(error);
        }
    }
    
    async sendPutRequest(endpoint, body) {
        try {
            const response = await fetch(endpoint, {
                method: 'PUT',
                headers: this.headers,
                body: JSON.stringify(body)
            });
            
            return response.json();
        }
        
        catch(error) {
            console.error(error);
        }
    }
}