import axios from 'axios';

const apiClient = window.axios;

apiClient.defaults.baseURL = '/api';
apiClient.defaults.withCredentials = true;
apiClient.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
apiClient.defaults.headers.common.Accept = 'application/json';

export default apiClient;
