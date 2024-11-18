import { instance } from '@/services/instances.service';

export const fileDownload = async (hash) => {
    try {
        const response = await instance.get(`/api/v1/services/file/${hash}`, { responseType: 'blob'});
        return response.data;
    } catch (error) {
        return error.response
    }
}

export const uploadFile = async ( file ) => {
    try {
        const response = await instance.post(`/api/v1/services/file/upload/`, file, { headers: {'Content-Type': 'multipart/form-data'} });
        return response
    } catch (error) {
        return error.response
    }
}