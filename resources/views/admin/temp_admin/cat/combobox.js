
// 목록을 txt 로 변환해서 리턴
function combobox_list2text(obj){

	var txt='';
	txt+=obj.options[0].value;
	for(var i=1;i<obj.options.length;i++){
		txt+='|'+obj.options[i].value;
	}

	return txt;
}

/** 
* 왼쪽필드에 옵션을 추가해줌
* @param value:Mixed (옵션의 value)
* @param text:String (옵션의 text)
*/
function addItem(f,value,text,idx) {
	
	if(!is_added(f,value) && value!=''){
		if(idx!=''){
			oAll.options[idx].text = text;
			oAll.options[idx].value = value;
		}
		else {
			oAll=f;
			oAll.options.length++;
			oAll.options[oAll.options.length-1].text = text;
			oAll.options[oAll.options.length-1].value = value;
		}
	}
}

// 값이 들어가 있는지 검사
function is_added(f,v){
	oAll=f;

	for(var i=0;i<oAll.options.length;i++){
		if(oAll.options[i].value==v){
			return true;
		}
	}
	return false;
}

// A와 B를 자리만 바꿈 sswap(A,B)
function sswap(f,a,b) {
	obj=f;
//	if (obj.selectedIndex < 1 ) return;

//	if(obj.options.length<1)return;
	if(a>=obj.options.length || b>=obj.options.length){
		return;
	}

	var a_Val = obj.options[a].value;
	var a_Txt = obj.options[a].text;
	var b_Val = obj.options[b].value;
	var b_Txt = obj.options[b].text;

	obj.options[a].value = b_Val;
	obj.options[a].text = b_Txt;
	obj.options[b].value = a_Val;
	obj.options[b].text = a_Txt;

}

// 변경
function smod(f,idx,new_val,new_text) {
	obj=f;
	obj.options[idx].value=new_val;
	obj.options[idx].text=new_text;
}

function sdel(f){
//	obj.selectedIndex = obj.selectedIndex -1;
	obj=f;

	if(obj.selectedIndex==0 && obj.options.length==1){
		obj.options.length=0;
		return;
	}
	for(var i=obj.selectedIndex;i<obj.options.length-1;i++){
	//	alert(i+':'+(i+1));
		sswap(i,i+1);
	}
//	obj.selectedIndex=0;

	obj.options.length=obj.options.length-1;
	

}

/** 
* selectbox의 항목을 모두 지움
* @param obj:Object (적용할 select오브젝트)
*/
function clearAll(obj) {
    try {
        obj.options.length = 0;
    } catch(e) {
        // do nothing
    }
}

/** 
* 선택한 옵션 아이템을 바로위 아이템과 자리바꿈
* @param obj:Object (적용할 select 엘리먼트)
*/
function moveUp(obj) {
    if (obj.selectedIndex < 1 ) return;
    var tmpVal = obj.options[obj.selectedIndex].value;
    var tmpTxt = obj.options[obj.selectedIndex].text;
    var swapVal = obj.options[obj.selectedIndex-1].value;
    var swapTxt = obj.options[obj.selectedIndex-1].text;
    obj.options[obj.selectedIndex].value = swapVal;
    obj.options[obj.selectedIndex].text = swapTxt;
    obj.options[obj.selectedIndex-1].value = tmpVal;
    obj.options[obj.selectedIndex-1].text = tmpTxt;
    obj.selectedIndex = obj.selectedIndex -1;
}

/** 
* 선택한 옵션 아이템을 바로아래 아이템과 자리바꿈
* @param obj:Object (적용할 select 엘리먼트)
*/
function moveDown(obj) {
    if (obj.selectedIndex == -1 || obj.selectedIndex == obj.options.length-1) return;
    var tmpVal = obj.options[obj.selectedIndex].value;
    var tmpTxt = obj.options[obj.selectedIndex].text;
    var swapVal = obj.options[obj.selectedIndex+1].value;
    var swapTxt = obj.options[obj.selectedIndex+1].text;
    obj.options[obj.selectedIndex].value = swapVal;
    obj.options[obj.selectedIndex].text = swapTxt;
    obj.options[obj.selectedIndex+1].value = tmpVal;
    obj.options[obj.selectedIndex+1].text = tmpTxt;
    obj.selectedIndex = obj.selectedIndex +1;
}

