import { AuthSdkError } from '@okta/okta-auth-js';

export default {
    oidc: {
        clientId: '0oa2jr6t74AkegzRD3l7',
        issuer: 'https://portal-test.edpass.sa.edu.au/oauth2/default',
        redirectUri: 'http://localhost:8000/login/callback',
        scopes: ['openid', 'profile', 'email', 'edspark'],
        pkce: true,
        logLevel: AuthSdkError,
    }
}
