import { AuthSdkError } from '@okta/okta-auth-js';

export default {
    oidc: {
        // clientId: '0oa1xyje6zV6eKawK3l7',
        clientId: '0oa1x7h530VPdjQlQ3l7',
        issuer: 'https://portal-test.edpass.sa.edu.au/oauth2/default',
        redirectUri: 'http://localhost:8000/dashboard',
        // redirectUri: window.location.origin + '/login/callback',
        scopes: ['openid', 'profile', 'email', 'edspark'],
        // tokenManager: {
        //     storage: 'localStorage'
        // },
        pkce: true,
        logLevel: AuthSdkError,
    }
}
