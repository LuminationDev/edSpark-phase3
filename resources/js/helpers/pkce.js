export const generateCodeVerifier = () => {
    const characters =
      'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_';
    const codeVerifier = Array.from(crypto.getRandomValues(new Uint8Array(43)))
      .map((x) => characters[x % characters.length])
      .join('');
    return codeVerifier;
  };

  export const generateCodeChallenge = (codeVerifier) => {
    const encoder = new TextEncoder();
    const data = encoder.encode(codeVerifier);
    const buffer = data.buffer;
    return base64UrlEncode(buffer);
  };

  const base64UrlEncode = (buffer) => {
    const base64 = btoa(String.fromCharCode(...new Uint8Array(buffer)));
    return base64.replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
  };
