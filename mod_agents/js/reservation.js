var loading='<img src="./images/loading.gif" width="50" />';
var loading_app='<center><img src="./images/loading-app.gif"/></center>';

var sPath = window.location.pathname;
var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);

if(sPage=='index.php'||sPage=='')
    window.onbeforeunload=clear_buffer;
function clear_buffer(){
    new Ajax.Request('./clearBuffers.php');
}

function show_back_date(){
    
    var _back=document.getElementById('_back_date');

    if(_back.disabled==false) _back.disabled=true;
    else _back.disabled=false;

    if(_back.style.display=='') _back.style.display='none';
    else _back.style.display='';

    document.getElementById('opened_twoway').checked=false;
}

function deselect_twoway(){
    var _back=document.getElementById('_back_date');

    _back.disabled=true;

    _back.style.display='none';
    
    document.getElementById('twoway').checked=false;
}

/*function clear_all_seats(){
    var seats
    seats=document.getElementById('oneway').childNodes;
    for(var i=0;i<seats.length;i++){
        if(seats[i].className=='clicked') seats[i].className='notreserved';
    }
    seats=document.getElementById('back_way').childNodes;
    for(i=0;i<seats.length;i++){
        if(seats[i].className=='clicked') seats[i].className='notreserved';
    }
}*/

function set_place(form,val,obj){
    document.getElementById('reserve_place'+form).value=val;
    var seats;
    if(form=='_back'){
        seats=document.getElementById('twoway_back').childNodes;
    }else{
        seats=document.getElementById('oneway').childNodes;
    }

    for(var i=0;i<seats.length;i++){
        if(seats[i].className=='clicked') seats[i].className='notreserved';
    }

    obj.className='clicked';
}

function show_price_discount(obj){
    document.getElementById('price_container').innerHTML=obj.options[obj.selectedIndex].id;
}

function refresh_seats(back_line,query_string){
    if(back_line==0){
        new Ajax.Updater('oneway','./busSeats.php?'+query_string,{asynchronous:true});
    }else if(back_line==1){
        new Ajax.Updater('twoway_back','./busSeats.php?back&'+query_string,{asynchronous:true,onLoading: function(){$('twoway_back').innerHTML=loading;}});
        new Ajax.Updater('oneway','./busSeats.php?'+query_string,{asynchronous:true,onLoading: function(){$('oneway').innerHTML=loading;}});        
    }
}

function move_from_cart(){
    new Ajax.Request('./move_from_cart.php');
    window.location='./search.php';
}

function confirm_purchase(){
    new Ajax.Updater('main_window','./pay.php',{asynchronous:true});
}

function max_symbols(object,symbol_count){
    if(object.value.length>=symbol_count) return false;
    else return true;
}

function select_place(object, place){
    var inactive_places=document.getElementById('inactive_places');
    if(object.className=='notreserved'){
        object.className='reserved';
        if(inactive_places.value.length>0) inactive_places.value = inactive_places.value + ',' + place;
        else inactive_places.value = place;
    }else if(object.className=='reserved'){
        object.className='notreserved';
        var buffer = inactive_places.value.split(',');
        var final_array=new Array;
        for(var i=0; i<buffer.length; i++){
            if(buffer[i]!=place) final_array.push(buffer[i]);
        }

        inactive_places.value=final_array.join(',');
    }
}