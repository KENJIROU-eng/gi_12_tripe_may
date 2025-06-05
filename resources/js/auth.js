// auth.js
export function login(email, password) {
    // axios はHTTPクライアントライブラリで、サーバーにリクエストを送るために使います。
    // ここでは、/login エンドポイントに POST リクエストを送っています。
    // リクエストの中身は { email, password } で、これは ログインに必要なユーザーのメールアドレスとパスワード。
    return axios.post('/login', { email, password })
        .then(res => {
        const token = res.data.token;
        localStorage.setItem('api_token', token);
        return token;
        });
    }
