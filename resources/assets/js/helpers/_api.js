// eslint-disable-next-line import/no-extraneous-dependencies
import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common.Accept = 'application/json';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';

export const baseURL = 'https://logobox3d.com/';

export function httpGet(url, payload = {}, headers = {}) {
  // eslint-disable-next-line no-param-reassign
  url = baseURL + url;
  return axios.get(url, {
    params: payload,
  }, headers);
}

export function httpPost(url, payload = {}, headers = {}) {
  // eslint-disable-next-line no-param-reassign
  url = baseURL + url;
  return axios.post(url, payload, headers);
}


export function httpDownload(url, payload = {}) {
  return axios({
    baseURL,
    headers: {},
    method: 'POST',
    responseType: 'blob',
    url,
    data: payload,
  });
}
