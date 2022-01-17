
export function $set(target,field,value){
	target[field] = value;
}

export function $del(target,field,value){
	delete target[field];
}

