export default {
    oidc: {
        clientId: '0oa1x7h530VPdjQlQ3l7',
        issuer: 'https://portal-test.edpass.sa.edu.au/oauth2/default',
        redirectUri: 'http://localhost:8000/dashboard',
        scopes: ['openid', 'profile', 'email', 'edspark'],
        tokenManager: {
            storage: 'localStorage'
        }
    }
}
