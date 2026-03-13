window.API = {
  base: '/api/v1',

  getToken(){
    return localStorage.getItem('api_token') || '';
  },

  async req(path, opts = {}){
    const headers = opts.headers || {};
    headers['Accept'] = 'application/json';

    // kalau body JSON
    if (opts.json) {
      headers['Content-Type'] = 'application/json';
      opts.body = JSON.stringify(opts.json);
    }

    const token = this.getToken();
    if (token) headers['Authorization'] = 'Bearer ' + token;

    const res = await fetch(this.base + path, { ...opts, headers });

    // auto handle unauthorized
    if (res.status === 401) {
      localStorage.removeItem('api_token');
      if(location.pathname.startsWith('/app')) location.href = '/login';
      throw new Error('Unauthenticated');
    }

    const ct = res.headers.get('content-type') || '';
    if (ct.includes('application/json')) return await res.json();
    return await res.text();
  },

  get(path){ return this.req(path); },
  post(path, json){ return this.req(path, { method:'POST', json }); },
  put(path, json){ return this.req(path, { method:'PUT', json }); },
  patch(path, json){ return this.req(path, { method:'PATCH', json }); },
  del(path){ return this.req(path, { method:'DELETE' }); },
};
