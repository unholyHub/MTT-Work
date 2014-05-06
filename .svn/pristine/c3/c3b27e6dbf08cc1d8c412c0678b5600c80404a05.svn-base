var loading='<img src="./images/loading.gif" width="50" />';
var loading_app='<center><img src="./images/loading-app.gif"/></center>';

var sPath = window.location.pathname;
var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);

/*if(sPage=='index.php'||sPage=='')
    window.onbeforeunload=clear_buffer;
function clear_buffer(){
    new Ajax.Request('./clearBuffers.php');
}*/

function show_back_date(){
    
    var _back=document.getElementById('_back_date');
    

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
    if(object.className == 'notreserved'){
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
var ajax_loader = server.projectPath + "/public/ajax_loader/ajax-loader.gif",
    htmlAjaxLoader = "<center><img src='" + ajax_loader + "'/></center>";
$(function () {
    $(".fancyboxLink").fancybox({
        overlayShow: true,
        transitionIn: "elastic",
        transitionOut: "elastic",
        titlePosition: "outside",
        overlayColor: "#000",
        overlayOpacity: 0.9
    });
    $(".dialog").dialog({
        autoOpen: true
    });
    $(".placeholder").placeholder();
    $(".bubbleInlineCities").bubble({
        position: "top",
        left: 5,
        top: 95,
        width: "195px",
        message: "cities"
    });
    $(".radioBoxContainers").click(function () {
        var a = $(this).parent(),
            b = $(this).children("div").eq(0);
        a.find('input[type="radio"]').attr("checked", false);
        a.find(".greenRadio").css("backgroundPosition", "-17px -39px");
        b.children('input[type="radio"]').attr("checked", true);
        b.css("backgroundPosition", "-17px -26px")
    });
    $("#oneWayTicket").click(function () {
        $(".discardReturnWay").hide()
    });
    $(".returnTrip").click(function () {
        $(".discardReturnWay").show()
    });
    $("#pointDeparture").keyup(function (a) {
        var b = $(this).val();
        getSuggestions(b, a, $("#appenderFromCity"), $("#searchingTimeTables"), "/cities/prefixFromCitySuggestion", $(this), $("#pointArrival"))
    });
    $("#pointArrival").keyup(function (a) {
        var b = $(this).val();
        getSuggestions(b, a, $("#appenderToCity"), $("#searchingTimeTables"), "/cities/prefixToCitySuggestion", $(this), $("#departureDay"))
    });
    $("#transportCompany").keyup(function (a) {
        var b = $(this).val();
        getSuggestions(b, a, $("#appenderCompany"), $("#searchingCompanies"), "/transportcompanyusers/gettransportcompanyuserssuggestions", $(this), null)
    });
    $("#getCitiesForBusStations").keyup(function (a) {
        var b = $(this).val();
        getSuggestions(b, a, $("#appenderCitiesForBusStations"), $("#searchingCitiesForBusStations"), "/cities/getcitiessuggestionsforbusstations", $(this), null)
    });
    $("#getCitiesForRailwayStations").keyup(function (a) {
        var b = $(this).val();
        getSuggestions(b, a, $("#appenderCitiesForRailwayStations"), $("#searchingCitiesForRailwayStations"), "/cities/getcitiessuggestionsforrailwaystations", $(this), null)
    });
    $(".greenSelectController").click(function () {
        var a = $(this).find("select");
        if (a.is(":visible")) {
            a.hide()
        } else {
            a.show()
        }
    });
    $(".greenSelectController").mouseenter(function () {
        $(this).attr("data-hover", 1)
    });
    $(".greenSelectController").mouseleave(function () {
        $(this).attr("data-hover", 0)
    });
    $(".greenSelect").change(function () {
        var a = jQuery.trim($(this).children("option:selected").text());
        $(this).parent().parent().children(".greenInputsMiddle").children(".valueGreenSelect").text(a)
    });
    $(".filterSublinesInLine").change(function () {
        filterSublinesInLine()
    });
    $(".filterSimularSublines").change(function () {
        similarSublines()
    });
    $(".bgr-searh-form-field").focusout(editIframeApi);
    $(".js-accordeon-element").click(function () {
        var b = $(this).parent(),
            d = $(this).parent().find("ul"),
            a = null,
            c = b.find(".subline-info-hour-accordeon-text");
        if (d.is(":visible")) {
            d.slideUp();
            if (c.data("text-slidedown")) {
                a = c.data("text-slidedown")
            }
        } else {
            d.slideDown();
            if (c.data("text-slideup")) {
                a = c.data("text-slideup")
            }
        }
        c.children().removeClass("info-arrow-closed info-arrow-open").addClass(a)
    });
    $(".mobile-footer-close").click(function () {
        $(".mobile-footer").remove()
    });
    resizeGoogleButtonTopOfScreen(false);
    $(window).resize(function () {
        resizeGoogleButtonTopOfScreen(true)
    });
    $(window).scroll(function () {
        resizeGoogleButtonTopOfScreen(true)
    })
});
$(document).on("click", ".ajaxPaginate a", function () {
    var b = $(this).attr("href"),
        a = jQuery.trim($("#ajaxPaginateLink").text());
    $.ajax({
        type: "GET",
        url: a + b,
        beforeSend: function () {
            $("#ajaxContainer").html(htmlAjaxLoader)
        },
        success: function (c) {
            closeAllSuggestions();
            $("#ajaxContainer").html(c)
        }
    });
    return false
});
$(document).on("click", ".js-location-switcher", function () {
    if ($(this).data("from") && $(this).data("to")) {
        var b = jQuery.trim($(this).data("from")),
            a = jQuery.trim($(this).data("to"));
        $("#pointDeparture").val(b);
        $("#pointArrival").val(a);
        $("#searchingTimeTables").submit()
    }
});
$(document).on("click", "body", function () {
    $(".greenSelectController").each(function () {
        var a = $(this).find("select");
        if ($(this).attr("data-hover") == 0) {
            a.hide()
        }
    })
});
$(document).on("click", ".js-show-line-timetable", function () {
    if ($(this).data("line-id") && $(this).data("transport-company") && $(this).data("timetable-dayofweek") && $(this).data("timetable-hour")) {
        var a = {
            lineId: $(this).data("line-id"),
            transportCompany: $(this).data("transport-company"),
            dayofweek: $(this).data("timetable-dayofweek"),
            hour: $(this).data("timetable-hour")
        };
        var b = $(this).attr("href");
        $.ajax({
            url: b,
            type: "POST",
            contentType: "application/json",
            timeout: 10000,
            dataType: "html",
            data: JSON.stringify(a),
            success: function (c) {
                loadAjaxPopup(c)
            }
        })
    }
    return false
});
$(document).on("click", ".js-show-subline-signal", function () {
    if ($(this).data("subline-id") && $(this).data("transport-company") && $(this).data("timetable-dayofweek") && $(this).data("timetable-hour")) {
        var a = {
            sublineId: $(this).data("subline-id"),
            transportCompany: $(this).data("transport-company"),
            dayofweek: $(this).data("timetable-dayofweek"),
            hour: $(this).data("timetable-hour")
        };
        var b = $(this).attr("href");
        $.ajax({
            url: b,
            type: "POST",
            contentType: "application/json",
            timeout: 10000,
            dataType: "html",
            data: JSON.stringify(a),
            success: function (c) {
                loadAjaxPopup(c)
            }
        })
    }
    return false
});
$(document).on("click", ".js-ajax-popup-wrapper-close", function () {
    $(this).parent().remove();
    $(".js-body-cloak").remove()
});

function submitSearchingFormTimeTables(b) {
    var a = $("#pointDeparture").val(),
        d = $("#pointArrival").val();
    if (a.length > 1 && d.length > 1) {
        var e = b.attr("action"),
            c = b.serialize();
        $.ajax({
            type: "GET",
            url: e,
            data: c,
            beforeSend: function () {
                $("#pageContent").html(htmlAjaxLoader)
            },
            success: function (f) {
                $("#pageContent").html(f);
                sublineSearchBufferToggleButton()
            }
        })
    } else {
        if (a.length == 0) {
            $("#pointDeparture").focus()
        } else {
            if (d.length == 0) {
                $("#pointArrival").focus()
            }
        }
    }
    return false
}

function filterSublinesInLine() {
    var d = $("#sublineFilterFromCity").val(),
        c = $("#sublineFilterToCity").val(),
        a = null,
        b = null;
    if (d != null && c != null) {
        $(".sublineBox").each(function () {
            a = $(this).children(".sublineBoxFromCity").val();
            b = $(this).children(".sublineBoxToCity").val();
            if (a == d && b == c) {
                $(this).show()
            } else {
                $(this).hide()
            }
        })
    } else {
        if (d != null) {
            $(".sublineBox").each(function () {
                a = $(this).children(".sublineBoxFromCity").val();
                if (a == d) {
                    $(this).show()
                } else {
                    $(this).hide()
                }
            })
        } else {
            if (c != null) {
                $(".sublineBox").each(function () {
                    b = $(this).children(".sublineBoxToCity").val();
                    if (b == c) {
                        $(this).show()
                    } else {
                        $(this).hide()
                    }
                })
            }
        }
    }
    return false
}

function similarSublines() {
    var b = $("#similarSublinesFilterCompanies").val(),
        a = null;
    if (b != null) {
        $(".sublineBox").each(function () {
            a = $(this).children(".sublineBoxTransportCompany").val();
            if (a == b) {
                $(this).show()
            } else {
                $(this).hide()
            }
        })
    }
}

function bookmark(a, b) {
    if ((navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >= 4)) {
        window.external.AddFavorite(a, b)
    } else {
        if (window.sidebar) {
            window.sidebar.addPanel(b, a, "")
        } else {
            alert("Press CTRL + D to bookmark")
        }
    }
}

function getSuggestions(g, b, c, a, e, d, f) {
    if (g.length > 1) {
        if (b.keyCode != 13 && b.keyCode != 8) {
            $.get(server.projectPath + e, a.serialize(), function (k) {
                if (k.length > 1) {
                    var j = $(k),
                        h = j.find(".suggestionText"),
                        i = h.length;
                    if (i == 1 && j != null) {
                        var l = jQuery.trim(h.text());
                        d.val(l);
                        c.hide();
                        d.focusout();
                        f.focus()
                    } else {
                        c.html(k);
                        c.show()
                    }
                } else {
                    c.hide()
                }
            })
        } else {
            serializeFilterForm(a)
        }
    } else {
        c.hide()
    }
}

function serializeFilterForm(a) {
    resetPlaceHolder();
    var b = a.serialize(),
        c = a.attr("action");
    scrollToElement($("#ajaxContainer"));
    $.ajax({
        type: "GET",
        url: c,
        data: b,
        beforeSend: function () {
            $("#ajaxContainer").html(htmlAjaxLoader)
        },
        success: function (d) {
            closeAllSuggestions();
            $("#ajaxContainer").html(d)
        }
    });
    return false
}

function resetPlaceHolder() {
    $(".placeholder").each(function () {
        if ($(this).attr("placeholder") == $(this).val()) {
            $(this).val("")
        }
    })
}

function scrollToElement(a) {
    $("html, body").animate({
        scrollTop: a.offset().top
    }, 1000)
}

function suggestionClose(b) {
    var a = b.parent().attr("index");
    $(".suggestionAppendValue" + a).unbind("keyup");
    closeSuggestion(b)
}

function closeSuggestion(a) {
    var b = a.parent();
    b.hide();
    b.html("")
}

function closeAllSuggestions() {
    $(".suggestionCloseButton").each(function () {
        closeSuggestion($(this))
    })
}

function setToInputValue(c, d) {
    var a = c.parent().parent(),
        e = a.attr("index");
    $(".suggestionAppendValue" + e).val(d);
    var b = a.find(".suggestionCloseButton");
    closeSuggestion(b)
}

function openDatePicker(a) {
    $(a).datepicker("show")
}

function submitForm(a) {
    $(a).submit()
}

function disableFormEnterKey(b) {
    var a;
    if (window.event) {
        a = window.event.keyCode
    } else {
        a = b.which
    } if (a == 13) {
        return false
    } else {
        return true
    }
}

function redirect_to(a) {
    window.location = server.domain + a
}

function setScrollToElement(a) {
    scrollToElement($(a))
}

function sublinesPagination(a) {
    var b = a.attr("href");
    removePaginationButton(a);
    $.ajax({
        type: "GET",
        url: b,
        beforeSend: function () {
            var c = '<img class="sublineSearchBuffer-conteiner-pagination-loader" src="' + ajax_loader + '"/>';
            $("#sublineSearchBuffer-conteiner-pagination").append(c)
        },
        success: function (c) {
            $("#sublineSearchBuffer-conteiner-pagination").append(c);
            $(".sublineSearchBuffer-conteiner-pagination-loader").remove();
            $("#sublineSearchBuffer-conteiner-pagination .sublineSearchBuffer-conteiner-lines:last").slideDown("slow");
            sublineSearchBufferToggleButton()
        }
    });
    return false
}

function removePaginationButton(a) {
    a.parent().remove()
}

function sublineSearchBufferToggleButton() {
    $(".sublineSearchBufferToggleButton").toggle(function () {
        var a = $(this).parent().parent().parent().parent();
        a.children(".sublineSearchBufferCell").removeClass("displayNone");
        showSiblingsChildrendsAndHideVisibleElement($(this))
    }, function () {
        var a = $(this).parent().parent().parent().parent();
        a.children(".sublineSearchBufferCell").addClass("displayNone");
        showSiblingsChildrendsAndHideVisibleElement($(this))
    })
}

function showSiblingsChildrendsAndHideVisibleElement(b) {
    var a = b.children(":visible");
    a.hide();
    a.siblings().show()
}

function editIframeApi() {
    var a = $("#bgr-searh-form-width").val(),
        h = $("#bgr-searh-form-height").val(),
        b = $("#bgr-searh-form-lang").val();
    if (a.length == 0 || h.length == 0) {
        a = "468";
        h = "60"
    }
    if (b == null) {
        b = "en"
    }
    var c = document.createElement("iframe");
    c.width = a;
    c.height = h;
    c.frameBorder = "no";
    c.src = server.domain + "/" + b + "/searh-form";
    var g = $(".bgr-searh-form-js");
    g.children("iframe").remove();
    var d = document.getElementsByTagName("bgr:searh-form")[0];
    d.parentNode.insertBefore(c, d);
    d.parentNode.insertBefore(c, d);
    var f = "",
        i = "";
    if (a != 468 || h != 60) {
        f = ' width="' + a + '"';
        i = ' height="' + h + '" '
    }
    var e = '<bgr:searh-form lang="' + b + '"' + f + i + "></bgr:searh-form>";
    $("#bgr-searh-textarea-code").val(e)
}

function loadAjaxPopup(f) {
    loadBodyCloak();
    var a = $(window).height(),
        b = $(window).scrollTop(),
        c = $(window).width();
    $("body").append(f);
    var e = $(".js-ajax-popup-wrapper"),
        h = e.width(),
        g = e.height(),
        i = ((c - h) / 2) + "px",
        d = b + ((a - g) / 2);
    e.css({
        left: i,
        top: d + "px"
    });
    $(window).scroll(function () {
        b = $(window).scrollTop();
        d = b + ((a - g) / 2);
        e.css({
            top: d + "px"
        })
    })
}

function loadBodyCloak() {
    var b = $(document).height();
    var a = $("<div/>");
    a.css({
        width: "100%",
        height: b + "px",
        "background-color": "#000",
        opacity: ".4",
        position: "absolute",
        top: 0,
        left: 0,
        "z-index": 9000
    });
    a.addClass("js-body-cloak");
    $("body").append(a)
}

function sendSublineSignal() {
    $(".ajax-popup-success").hide();
    var a = $("#ajax-new-subline-signal"),
        c = a.attr("action"),
        b = a.serialize();
    $.ajax({
        type: "POST",
        url: c,
        data: b,
        success: function () {
            a.find('input[type="text"]').val("");
            a.find("textarea").val("");
            $(".ajax-popup-success").fadeIn(1000).delay(4000).fadeOut(1000)
        }
    });
    return false
}

function resizeGoogleButtonTopOfScreen(c) {
    var a = $(".google-play-container");
    if (a.is(":visible")) {
        var d = a.height(),
            f = $(window).height(),
            b = $(window).scrollTop(),
            e = (f - d) / 2 + b;
        if (e > 0) {
            if (c) {
                a.stop().animate({
                    top: e
                })
            } else {
                a.css("top", e)
            }
        }
    }
};