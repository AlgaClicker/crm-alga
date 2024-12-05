export function deleteEmptyField(value){
    
    let keys = Object.keys(value)
    
    keys.forEach(key => {

        if(value[key] && typeof value[key] == "object"){
            deleteEmptyField(value[key])
        }else if(value[key] == null || typeof value[key] === "undefined"){
            delete value[key]
        }
    })

    return value
}

export function clearField(value){
    let keys = Object.keys(value)

    keys.forEach(key => {

        if(value[key] && typeof value[key] == "object"){
            deleteEmptyField(value[key])
        }else{
            value[key] = null
        }
    })

    return value
}