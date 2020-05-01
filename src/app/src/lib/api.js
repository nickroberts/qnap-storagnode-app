import axios from 'axios';

export const start = () => {
  console.log('start');
};

export const stop = () => {
  console.log('stop');
};

export const restart = () => {
  console.log('restart');
};

export const update = () => {
  console.log('update');
};

export const getStatus = async () => {
  const { data } = await axios.get('/api.php', { params: { action: 'check' } });
  return data.data;
};

export const getConfig = async () => {
  const { data } = await axios.get('/api.php', { params: { action: 'config' } });
  return data.data;
};

export const saveConfig = async configData => {
  const { data } = await axios.post('/api.php', { action: 'config', data: configData });
  return data.data;
};

export const tailIdentityLog = async (lines = 50) => {
  const { data } = await axios.get('/api.php', { params: { action: 'tailIdentity', lines } });
  return data.data;
};

export const tailLog = async (lines = 50) => {
  const { data } = await axios.get('/api.php', { params: { action: 'tail', lines } });
  return data.data;
};
