export default {
    methods: {
        csvExportMaterial(data){
            console.log(data.data)
            if(data.type == 'material'){
                var csv = ['Позиция,Название,Тип марка,Код продукции,Завод производитель,Ед.изм,Количество,Примечание\r\n']
                csv.push(data.data.filter( item => item.fullname != '' && item.unit != '')
                    .map( item => ( typeof item.position != 'undefined' ? item?.position : '' )
                        + ',' + ( typeof item.fullname != 'undefined' ? item?.fullname : '' )
                        + ',' + ( typeof item.type != 'undefined' ? item?.type : '' )
                        + ',' + ( typeof item.code != 'undefined' ? item?.code : '' )
                        + ',' + ( typeof item.vendor != 'undefined' ? item?.vendor : '' )
                        + ',' + ( typeof item.unit != 'undefined' ? item?.unit : '' )
                        + ',' + ( typeof item.quantity != 'undefined' ? item?.quantity : '' )
                        + ',' + ( typeof item.description != 'undefined' ? item?.description : '' ) + "\r\n"
                    ))
                
                let a = document.createElement("a");
                let file = new Blob([csv], {type: 'text/csv;charset=utf-8'});
                a.href = URL.createObjectURL(file);
                a.download = `${data.name}.csv`;
                a.click();
            }

        }  
    },
};
