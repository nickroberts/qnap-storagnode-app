export const authorizationTokenRules = [v => !!v || 'Authorization token is required'];

export const emailAddressRules = [
  v => !!v || 'E-mail address is required',
  v => /.+@.+/.test(v) || 'E-mail must be valid'
];

export const hostnameRules = [
  v => {
    const message = 'Protocol, hostname and port are required';
    if (!v) {
      return message;
    }
    const [protocol, host] = v.split('://');
    if (protocol && host) {
      const [hostname, port] = host.split(':');
      return (protocol, hostname && port && !isNaN(port)) || message;
    }
    return message;
  }
];

export const identityPathRules = [v => (!!v && v.length > 1) || 'Identity path is required'];

export const storageAllocationRules = [v => (!!v && v > 0) || 'Storage allocation is required'];

export const storageDirectoryRules = [v => (!!v && v.length > 1) || 'Storage directory is required'];

// 0x0123456789012345678901234567890123456789
export const walletAddressRules = [
  v => (!!v && /^0x[a-fA-F0-9]{40}$/.test(v)) || 'A valid Ethereum wallet address is required'
];
