import axios from "axios";
//import store from "@/store";

export const instance = axios.create({
   baseURL: process.env.VUE_APP_URL,
});

instance.interceptors.request.use( config  => {
    const token = localStorage.getItem("token");
    config.headers.Authorization = token ? `Bearer ${token}` : "";
    return config;
});

instance.interceptors.response.use( response => {
    return response;
})
