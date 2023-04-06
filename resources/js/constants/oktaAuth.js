import { OktaAuth } from '@okta/okta-auth-js';
import oktaConfig from './oktaConfig';


const oktaAuth = new OktaAuth(oktaConfig.oidc);

export default oktaAuth;
