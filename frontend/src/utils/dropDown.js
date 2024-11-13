export function dropDownNavbar(){
   
    const buttonProfile = document.querySelector(".c-button-profile")
    const optionMenu = document.querySelector('.c-button-profile__drop-down')
    
    buttonProfile.addEventListener("click", () => {
        
            optionMenu.classList.toggle("c-button-profile__drop-down--active")
            var chevrons = document.getElementsByClassName('c-button-profile__chevron')

            if(chevrons.length > 0){
                chevrons[0].classList.toggle('c-button-profile__chevron--active')
                chevrons[1].classList.toggle('c-button-profile__chevron--active')
            }
        }
    );
}

export function dropDownFile() {

    const fileBlockMenu = document.querySelector(".c-file-block-menu")
    const optionMenu = document.querySelector('.c-file-block-menu__drop-down')

    fileBlockMenu.addEventListener("click", () => {
            optionMenu.classList.toggle("c-file-block-menu__drop-down--active")
        }
    );
}

export function ColseDropDown(){

    window.addEventListener('click', (event) => {
        
        if((event.target.className != 'c-tabel-material-body-groupe-row' && event.target.parentNode.className != 'SVGAnimatedStrin') && event.target.parentNode.nodeName != "SPAN" ){
           
            var optionMenu = document?.getElementsByClassName('c-button-profile__drop-down')
            var dialog = document?.getElementsByClassName('c-notifications-dialog')
            var chevrons = document?.getElementsByClassName('c-button-profile__chevron')

            if( event.target?.className != 'c-button-profile__user-info' && event.target.className != 'c-button-profile__drop-down' && event.target.className != 'c-username' && event.target.className != 'c-button-profile__wrapper' ){
                optionMenu[0]?.classList.remove("c-button-profile__drop-down--active")
                chevrons[0]?.classList.add('c-button-profile__chevron--active')
                chevrons[1]?.classList.remove('c-button-profile__chevron--active')
            }

            if( event.target.className != 'c-bell' && dialog.length > 1
                && event.target.parentNode.parentNode.className != 'icon-bell' 
                && event.target.parentNode.parentNode.className != 'icon'
                && event.target.parentNode.parentNode.className != 'c-notifications-wrap__messages'
                && event.target.className != 'c-notifications-wrap__header'
                && event.target.className != 'c-notifications-wrap__messages'
                && event.target.className != 'c-notification-list__right'
                && event.target.className != 'c-notification-list'
                && event.target.className != 'notification-header__title'
                && event.target.className != 'notification-header'
                && event.target.className != 'c-avatar'
            ){
                dialog[0].classList.remove("c-notifications-dialog--active")
            }   
        } 

    })
}