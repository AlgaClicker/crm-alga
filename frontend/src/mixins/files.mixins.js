import { fileDownload } from '@/services/files.service.js';

export default {
    methods: {
        async downloadFile(hash, name){
            
            let data = await fileDownload(hash) 

            const href = URL.createObjectURL(data)
            const link = document.createElement('a');
            link.href = href;

            link.setAttribute('download', name);
            document.body.appendChild(link, name);
            link.click();
        }  
    },
};