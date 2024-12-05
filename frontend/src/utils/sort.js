<<<<<<< HEAD
import store from '@/store'

export function sortHelpers(field, options, setOptionsActionsName, setListActionsName){
                
    let sort = {}
    console.log(options)
    console.log(field)
    console.log(setOptionsActionsName)
    console.log(setListActionsName)
    // options getter
    // set options actions
    // set list actions
    if(sort[field].asc == true){
        sort[field].asc = false
        sort[field].desc = true
    }else{
        sort[field].desc = false
        sort[field].asc = true
    } 
    
    if(sort[field].asc){
        let obj = {
            [field] : 'ASC'
        }
        options.orderBy = obj
    }else{
        let obj = {
            [field] : 'DESC'
        }
        options.orderBy = obj
    }
    
    for(var key in sort){
        if(key != field){
            sort[key].asc = false
            sort[key].desc = false
        }
    }
    
    store.dispatch(this.setOptionsActionsName, options)
    store.dispatch(this.setListActionsName)
}
    

=======
import store from '@/store'

export function sortHelpers(field, options, setOptionsActionsName, setListActionsName){
                
    let sort = {}
    console.log(options)
    console.log(field)
    console.log(setOptionsActionsName)
    console.log(setListActionsName)
    // options getter
    // set options actions
    // set list actions
    if(sort[field].asc == true){
        sort[field].asc = false
        sort[field].desc = true
    }else{
        sort[field].desc = false
        sort[field].asc = true
    } 
    
    if(sort[field].asc){
        let obj = {
            [field] : 'ASC'
        }
        options.orderBy = obj
    }else{
        let obj = {
            [field] : 'DESC'
        }
        options.orderBy = obj
    }
    
    for(var key in sort){
        if(key != field){
            sort[key].asc = false
            sort[key].desc = false
        }
    }
    
    store.dispatch(this.setOptionsActionsName, options)
    store.dispatch(this.setListActionsName)
}
    

>>>>>>> feature/f_requisitions_master
