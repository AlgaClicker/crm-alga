export function heightDialogsList(){
    var layoutHeaderHeight = document.getElementsByClassName('c-header')[0]?.clientHeight

    var chatMenuHeaderHeight = document.getElementsByClassName('l-chat_menu-header')[0]?.clientHeight
    
    var height = document.documentElement?.clientHeight - ( layoutHeaderHeight + 135 + chatMenuHeaderHeight)

    return height
}

export function heightMessagesList(){
    var layoutHeaderHeight = document.getElementsByClassName('c-header')[0]?.clientHeight

    var chatHeader = document.getElementsByClassName('l-chat_messages-header')[0]?.clientHeight
    var footerHeader = document.getElementsByClassName('l-chat_messages-footer')[0]?.clientHeight

    var height = document.documentElement?.clientHeight - ( layoutHeaderHeight + footerHeader + chatHeader + 5)

    return height

    // Материал заменяет заполенные строки
    // Select в accounts
    // Спека чистит аккаунты
    // Селект должностей 
    // В бригадах список бригад, список спецификаций, заявки, табеля 
    // автор

}     