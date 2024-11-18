export const size = function( size, decimals = 2 ){
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