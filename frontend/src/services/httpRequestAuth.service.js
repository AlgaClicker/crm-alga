import { instance } from '@/services/instances.service';

export const httpRequestAuth = async(url, method, data) => {
    try {
        if(method == 'delete'){
            const response = await instance[method](`${url}/`, { data: data } );
            return response.data;
        }
        else{
            const response = await instance[method](`${url}/`, data);
            return response.data;
        }
    } catch (error) {
        return error.response
    }
}
