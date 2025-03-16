import axios from 'axios';

// Automatically include the CSRF token in every request
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
