
export default  function toLine(value){
	return value.replace(/(A-Z)g/,'-$1').toLocaleLowerCase();
}
