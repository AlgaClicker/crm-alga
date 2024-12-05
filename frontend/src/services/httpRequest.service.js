import { instance } from '@/services/instances.service';

export const httpRequest = async(url, method, data) => {
    try {
        if(method == 'delete'){
            const response = await instance[method](`/api/v1/${url}`, { data: data } );
            return response.data;
        }
        else{
            const response = await instance[method](`/api/v1/${url}`, data);
            return response.data;
        }
    } catch (error) {
        return error.response
    }
}

