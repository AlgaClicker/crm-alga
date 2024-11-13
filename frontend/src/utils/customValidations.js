export function money(value) {
    if(value != null){
        if(String(value).match(/^-?[0-9]+(?:\.[0-9]{1,2})?$/) != null){
            return true
        }
        else{
            return false
        }
    }else{
        return false
    }
}
 
export function dateMin(value){
    let now = new Date
    return now < value
}

export function phoneNumber(value){
    
    if(value != null){
        if(String(value).match(/^\+7-\d\d\d-\d\d\d-\d\d-\d\d/) != null){
            return true
        }
        else{
            return false
        }
    }else{
        return false
    }
}