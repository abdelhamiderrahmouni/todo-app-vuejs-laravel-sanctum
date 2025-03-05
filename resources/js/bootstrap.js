import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL = import.meta.env.VITE_APP_URL
window.axios.defaults.withCredentials = true
window.axios.defaults.withXSRFToken = true

if (localStorage.getItem('token')) {
    window.axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`
}

export default axios
