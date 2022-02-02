import axios from 'axios';

window.axios = axios;
window.axios.defaults.headers.common.Accept = 'application/json';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';

export const baseURL = 'http://dev.eney.com.ua:8082/';

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  // console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

export function httpGetProduct(url, payload = {}, headers = {},
  baseurl = baseURL, withcredentials = false) {
  // eslint-disable-next-line no-param-reassign
  url = baseurl + url;
  return axios.get(url, {
    withCredentials: withcredentials,
    params: payload,
  }, headers);
}

export function httpGet(url, payload = {}, headers = {},
  baseurl = baseURL, withcredentials = false) {
  // eslint-disable-next-line no-param-reassign
  url = baseurl + url;
  return axios.get(url, {
    withCredentials: withcredentials,
    params: payload,
  }, headers);
}

export function httpPost(url, payload = {}, headers = {}) {
  // eslint-disable-next-line no-param-reassign
  url = baseURL + url;
  return axios.post(url, payload, headers);
}

export function httpDelete(url, payload = {}, headers = {}) {
  // eslint-disable-next-line no-param-reassign
  url = baseURL + url;
  return axios.delete(url, payload, headers);
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
