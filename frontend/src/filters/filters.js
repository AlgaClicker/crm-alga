export const dateFilter = ( date ) => {
    var newDate = new Date(date);  
    return newDate.toLocaleString();
}

export const sizeFilter = ( size, decimals = 2 ) => {
    if (size === 0) {
		return '0';
	} else {
		var k = 1024;
		var dm = decimals < 0 ? 0 : decimals;
		var sizes = ['байт', 'КБ', 'МБ', 'ГБ', 'ТБ'];
		var i = Math.floor(Math.log(size) / Math.log(k));
		return isNaN(parseFloat((size / Math.pow(k, i)).toFixed(dm))) ? " " : parseFloat((size / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
	}
}

export const moneyFilter = ( value ) => {
	
	if(typeof value != 'undefined' | value != null){
		var money = value.toLocaleString(undefined, {
			minimumFractionDigits: 2,
			maximumFractionDigits: 2,
		}) + " руб"
		
	}

	return money
}

export const notificationsDateFilter = (value) => {

	let now = new Date
	let timeValue = new Date(value).toLocaleString([], { hour: '2-digit', minute:'2-digit' })

	let dateNow = now.toLocaleString([], { year: 'numeric', month: 'numeric', day: 'numeric' })
	let dateValue = new Date(value).toLocaleString([], { year: 'numeric', month: 'numeric', day: 'numeric' })

	if(
		dateNow.split('.').slice(1,2).join('') == dateValue.split('.').slice(1,2).join('') &&
		+dateNow.split('.')[0] - +dateValue.split('.')[0] == 1
	){
		return "вчера " + timeValue
	} 
	else if(dateValue == dateNow){
		return "сегодня " + timeValue
	}else {
		return new Date(value).toLocaleString()
	}
}

export const phoneFilter = ( value ) => {
	var phone = value.split('') 
	phone = phone[0] = '8'
	phone = '+' + phone.join('')

	return phone
}

export const paymentsTypeFilter = ( value ) => {
    if ( value == 'out') {
		return 'Исходящий';
	} else if( value == 'in' ) {
        return 'Входящий';
    } else if( value == 'advance'){
        return 'Аванс';
    } else if (value == 'salary') {
		return 'Заработная плата'
	} else if (value == 'other') {
		return 'Прочая выплата'
	}
}

export const dateFilterWithoutTime = ( date ) => {
    var newDate = new Date(date);  
    return newDate.toLocaleString([], { year: 'numeric', month: 'numeric', day: 'numeric' });
}

export const dateFilterWithoutDay = ( date ) => {

	console.log(date)

    return date.toLocaleString( 'ru-ru', { month:'short', year:'numeric' });
}

export const dateFilterMonth = (number) => {
	
	const months = ['январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь']

	return months[number]
}

export const boolFilter = (value) => {
	return value ? "да": "нет"
}