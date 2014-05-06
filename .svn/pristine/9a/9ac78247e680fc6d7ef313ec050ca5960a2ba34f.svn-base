var count=1;
function add_point(options){

    var newtr=document.createElement('div');
    newtr.className= "clearfix divBox";
    
    var newtd1=document.createElement('div');//busstop
    var newtd2=document.createElement('div');//pristiga
    var newtd3=document.createElement('div');//prestoi
    var newtd4=document.createElement('div');//zaminava
    
    
    var newselect=document.createElement('select');
    newtd1.innerHTML='<span class="GoAndBackStyle">Спирка #'+(count+1)+': </span>';
    newselect.setAttribute('name', 'points[point]['+count+']');
    newselect.innerHTML=options;
    newtd1.appendChild(newselect);
    
    newtd1.setAttribute('class','colDiv blockDivStopSelect');
    newtd2.setAttribute('class','colDiv labelText blockDivGoAndBack');
    newtd4.setAttribute('class','colDiv labelText blockDivGoAndBack');

    var newarrive_place_bg=document.createElement('input');
    newarrive_place_bg.setAttribute('name', 'points[bus_station_bg]['+count+']');
    newarrive_place_bg.setAttribute('class','inputAddLineSize');
    var newarrive_place_en=document.createElement('input');
    newarrive_place_en.setAttribute('name', 'points[bus_station_en]['+count+']');
    newarrive_place_en.setAttribute('class','inputAddLineSize');

    var newarrive_time=document.createElement('input')
    newarrive_time.setAttribute('name', 'points[arrival_time]['+count+']');
    newarrive_time.setAttribute('value', '00:00');
    newarrive_time.setAttribute('class', 'time-lenght');
    newtd2.innerHTML=newtd2.innerHTML+'<div class="GoAndBackStyle">Отиване</div> ';
    newtd2.innerHTML=newtd2.innerHTML+'<div>Име на спирка:</div> ';
    newtd2.appendChild(newarrive_place_bg);
    newtd2.innerHTML=newtd2.innerHTML+'<div>Bus station name:</div>';
    newtd2.appendChild(newarrive_place_en);
    newtd2.innerHTML=newtd2.innerHTML+'<div>';
    newtd2.innerHTML=newtd2.innerHTML+'Час';
    newtd2.appendChild(newarrive_time);
    newtd2.innerHTML=newtd2.innerHTML+'Престой';
    newtd2.innerHTML=newtd2.innerHTML+'</div>';

    var stopover=document.createElement('input');
    stopover.setAttribute('name', 'points[stopover]['+count+']');

    stopover.setAttribute('value', '0');
    stopover.setAttribute('class', 'stopover-lenght');
    newtd2.appendChild(stopover);

    var newdepart_place_bg=document.createElement('input');
    newdepart_place_bg.setAttribute('name', 'points[bus_station_back_bg]['+count+']');
    newdepart_place_bg.setAttribute('class','inputAddLineSize');
    var newdepart_place_en=document.createElement('input');
    newdepart_place_en.setAttribute('name', 'points[bus_station_back_en]['+count+']')
    newdepart_place_en.setAttribute('class','inputAddLineSize');

    var newdepart_time=document.createElement('input');
    newdepart_time.setAttribute('value', '00:00');
    newdepart_time.setAttribute('class', 'time-lenght');
    newdepart_time.setAttribute('name', 'points[arrival_time_back]['+count+']');
    newtd4.innerHTML=newtd4.innerHTML+'<div class="GoAndBackStyle">Връщане</div> ';
    newtd4.innerHTML=newtd4.innerHTML+'<div>Име на спирка:</div> ';
    newtd4.appendChild(newdepart_place_bg);
    newtd4.innerHTML=newtd4.innerHTML+'<div>Bus station name:</div>';
    newtd4.appendChild(newdepart_place_en);
    newtd4.innerHTML=newtd4.innerHTML+'<div>';
    newtd4.innerHTML=newtd4.innerHTML+'<label>Час</label>';
    newtd4.appendChild(newdepart_time);
    
    

    var stopover_back=document.createElement('input');
    stopover_back.setAttribute('name', 'points[stopover_back]['+count+']');

    stopover_back.setAttribute('value', '0');
    stopover_back.setAttribute('class', 'stopover-lenght');
    newtd4.innerHTML=newtd4.innerHTML+'<label>Престой</label>';
    newtd4.appendChild(stopover_back);
    newtd4.innerHTML=newtd4.innerHTML+'</div></div>';
    

    newtr.appendChild(newtd1);
    newtr.appendChild(newtd2);
    newtr.appendChild(newtd4);

    document.getElementById('point_container').appendChild(newtr);

    count++;//increment
    window.scrollTo(0, document.body.scrollHeight);
}


function calculate_departure(object,key){
    var hours=document.getElementById('arrival_time_'+key).value.split(':',2);
    var date = new Date();

    date.setHours(hours[0], parseInt(hours[1])+parseInt(object.value),'00');
    document.getElementById('departure_time_'+key).value=date.toLocaleTimeString();

}

function copy_departure(object,key){
    document.getElementById('departure_time_'+key).value=object.value;
}


function enable_date_to_date(check){
    if(check.checked==true){
        document.getElementById('points_from_date').disabled=false;
        document.getElementById('points_to_date').disabled=false;
    }else{
        document.getElementById('points_from_date').disabled=true;
        document.getElementById('points_to_date').disabled=true;
    }
}

//
//function enable_checkbox(check){
//    var enablingCheckbox =  document.getElementById('enablingCheckbox');
//    if(enablingCheckbox.checked==true){
//        document.getElementById('points_from_date').removeAttribute('disabled');
//        document.getElementById('points_to_date').removeAttribute('disabled');
//    }else{
//        document.getElementById('points_from_date').setAttribute('disabled','true');
//        document.getElementById('points_to_date').setAttribute('disabled','true');
//    }
//}


function set_type(id,type){
    document.getElementById(id).name='discount['+type.value+']';
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

function save_place(object, place){
    var inactive_places=document.getElementById('saved_places');
    if(object.className=='notreserved'){
        object.className='reserved';
        if(inactive_places.value.length>0) inactive_places.value = inactive_places.value + ',{' + place + '}';
        else inactive_places.value = '{' + place + '}';
    }else if(object.className=='reserved'){
        object.className='notreserved';
        var buffer = inactive_places.value.split(',');
        var final_array=new Array;
        for(var i=0; i<buffer.length; i++){
            if(buffer[i]!='{' + place + '}') final_array.push(buffer[i]);
        }

        inactive_places.value=final_array.join(',');
    }
}

