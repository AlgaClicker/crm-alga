export function heightBaseTable(){
    
    var layoutHeaderHeight = document.getElementsByClassName('c-header')[0]?.clientHeight

    var container

    if(document.getElementsByClassName('l-default').length != 0){
        container = document.getElementsByClassName('l-default')
    }

    if(document.getElementsByClassName('l-objects').length != 0){
        container = document.getElementsByClassName('l-objects')
    }
    
    if(document.getElementsByClassName('l-payments').length != 0){
        container = document.getElementsByClassName('l-payments')
    }

    if(document.getElementsByClassName('l-kadry-payments').length != 0){
        container = document.getElementsByClassName('l-kadry-payments')
    }

    var containerPadding =  window.getComputedStyle(container[0], null).padding;

    var padding = Number(containerPadding.split(" ")[1].split('p')[0])

    var tableFooterHeight = document.getElementsByClassName('c-table-footer')[0]?.clientHeight

    if( typeof tableFooterHeight == 'undefined'){
        tableFooterHeight = 0
    }

    var height = document.documentElement.clientHeight - ( 250 + layoutHeaderHeight + padding + tableFooterHeight)
    
    return height
}

export function heightDirectoryBodyMaterialTable(){

    var layoutHeaderHeight = document.getElementsByClassName('c-header')[0]?.clientHeight

    var container

    if(document.getElementsByClassName('l-default').length != 0){
        container = document.getElementsByClassName('l-default')
    }

    var containerPadding = window.getComputedStyle(container[0], null).padding;

    var padding = Number(containerPadding.split(" ")[1].split('p')[0])

    var tableHeaderHeight = document.getElementsByClassName('c-tabel-material-header')[0]?.clientHeight
    var tableFooterHeight = document.getElementsByClassName('c-tabel-material__footer')[0]?.clientHeight
    
    var height = document.documentElement?.clientHeight - ( 230 - layoutHeaderHeight + padding + tableHeaderHeight + tableFooterHeight)

    if( height < 500){
        height = 500
    }
    return height
}

export function heightMasterSpecificationsTable(){

    let layoutHeaderHeight = document.getElementsByClassName('c-header')[0]?.clientHeight
    let specMaster = window.getComputedStyle(document.getElementsByClassName('l-specification-master')[0]).paddingTop

    let specHeader = document.getElementsByClassName('l-specification-master__header')[0]?.clientHeight
    let breadcrumbHeight = document.getElementsByClassName('breadcrumb')[0]?.clientHeight

    let breadcrumb = document.getElementsByClassName('breadcrumb')

    let breadcrumbPadding = window.getComputedStyle(breadcrumb[0], null).marginTop;

    let marginBreadcrumb = Number(breadcrumbPadding.split('').reverse().slice(2, breadcrumbPadding.split('').length).reverse().join(''))
    
    specMaster = Number(specMaster.split('').reverse().slice(2, specMaster.split('').length).reverse().join(''))

    let height = document.documentElement?.clientHeight - ( marginBreadcrumb * 2 + specMaster + breadcrumbHeight + layoutHeaderHeight + specHeader)
    
    return height
}
