export const emailAddressRules = [
  (v) => !!v || "E-mail address is required",
  (v) => /.+@.+/.test(v) || "E-mail must be valid",
];

export const hostnameRules = [
  (v) => {
    const message = "Hostname and port are required";
    if (!v) {
      return message;
    }
    const [host, port] = v.split(":");
    return (host && port && !isNaN(port)) || message;
  },
];

export const identityPathRules = [
  (v) => (!!v && v.length > 1) || "Identity path is required",
];

export const authorizationTokenRules = [
  (v) => !!v || "Authorization token is required",
];

export const storageAllocationRules = [
  (v) => (!!v && v > 0) || "Storage allocation is required",
];

export const storageDirectoryRules = [
  (v) => (!!v && v.length > 1) || "Storage directory is required",
];

// 0x0123456789012345678901234567890123456789
export const walletAddressRules = [
  (v) =>
    (!!v && v.match(/^0x[a-fA-F0-9]{40}$/g)) ||
    "A valid Ethereum wallet address is required",
];
