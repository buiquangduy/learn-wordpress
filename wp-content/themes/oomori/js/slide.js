jQuery(function() {
  var firstview_list = {

    "index-img-slider-01.png" : ["#","slide 1"],
    "index-img-slider-02.png" : ["#","slide 2"],
    "index-img-slider-03.png" : ["#","slide 3"],
    "eof" : ""
  };

  // EOFを削除
  delete firstview_list["eof"];

  var firstview_action_timer;
  var firstview_action_span = 4000;
  var touch = 0;
  var tap = 0;
  var tap_event_timer;

  if(Object.keys(firstview_list).length > 0){
    var container = document.createElement("div");
    jQuery(container).attr("id", "firstview_container").addClass("clearfix alpha");

    var wrapper = document.createElement("div");
    jQuery(wrapper).attr("id", "firstview_wrapper").addClass("clearfix").appendTo(container);

    var inner = document.createElement("ul");
    jQuery(inner).attr("id", "firstview").addClass("clearfix").appendTo(wrapper);

    var filter_left = document.createElement("div");
    jQuery(filter_left).attr("id", "firstview_filter_left").addClass("firstview_filter").appendTo(wrapper);

    var filter_right = document.createElement("div");
    jQuery(filter_right).attr("id", "firstview_filter_right").addClass("firstview_filter").appendTo(wrapper);

    var arrow_left = document.createElement("a");
    jQuery(arrow_left).attr("id", "firstview_arrow_left").addClass("firstview_arrow remove").text("").appendTo(wrapper);

    var arrow_right = document.createElement("a");
    jQuery(arrow_right).attr("id", "firstview_arrow_right").addClass("firstview_arrow remove").text("").appendTo(wrapper);

    var controll = document.createElement("ul");
    jQuery(controll).attr("id", "firstview_controller").addClass("clearfix").appendTo(container);

    var n = 0;
    var h = Object.keys(firstview_list).length - 1;
    jQuery.each(firstview_list, function(k, v){

      var item = document.createElement("li");
      if(n < h){
        jQuery(item).attr("id", 'fv-' + n).appendTo(inner);
      }else{
        jQuery(item).attr("id", 'fv-' + n).prependTo(inner);
      }

      var anchor = document.createElement("a");
      jQuery(anchor).attr("href", v[0]).appendTo(item);

      var img = document.createElement("img");
      var app_root = 'wp-content/themes/oomori';
      jQuery(img).attr({src: app_root + "/img/" + k, alt: v[1]}).addClass("img-responsive aligncenter").appendTo(anchor);

      var controll_item = document.createElement("li");
      jQuery(controll_item).attr("id", 'ct-' + n).appendTo(controll);

      var controll_anchor = document.createElement("a");
      jQuery(controll_anchor).addClass("remove").text('').appendTo(controll_item);

      if(n == 0){
        jQuery(item).addClass("active");
        jQuery(controll_item).addClass("active");
      }

      n++;
    });


    jQuery(container).insertAfter("#header_wrap");

    firstview_resize();
    firstview_action_timer = setTimeout(firstview_action, firstview_action_span);

    jQuery(window, document, 'html, body').resize(function(){
      firstview_resize();
      firstview_action_timer = setTimeout(firstview_action, firstview_action_span);
    });

    jQuery("ul#firstview").mouseover(function(){
      clearTimeout(firstview_action_timer);
      touch = 1;
    }).mouseout(function(){
      if(tap != 1){
        touch = 0;
        firstview_action_timer = setTimeout(firstview_action, firstview_action_span * 0.5);
      }
    });

    /*
     var touchX = 0;
     var nextX = 0;
     jQuery("ul#firstview").bind({
     // フリック開始時
     "touchstart": function(e) {
     clearTimeout(firstview_action_timer);
     clearTimeout(tap_event_timer);
     touchX = event.changedTouches[0].pageX;
     },
     // フリック中
     "touchmove": function(e) {
     clearTimeout(firstview_action_timer);
     clearTimeout(tap_event_timer);
     e.preventDefault();
     nextX = event.changedTouches[0].pageX;
     },
     // フリック終了
     "touchend": function(e) {
     clearTimeout(firstview_action_timer);
     clearTimeout(tap_event_timer);
     tap = 1;
     if(touchX != nextX){
     _action((touchX > nextX ? 'right' : 'left'));
     }
     }
     });
     */

    jQuery("a.firstview_arrow").click(function(){
      touch = 0;
      if(!jQuery(this).hasClass("untouch")){
        _action(this.id.split("_")[2]);
      }
    });

    jQuery("ul#firstview_controller li a").click(function(){
      touch = 0;
      if(!jQuery(this).hasClass("active")){
        firtview_direct(jQuery(this).parent().attr("id").split("-")[1]);
      }
    });
  }

  function firstview_resize(){
    clearTimeout(firstview_action_timer);
    var w = jQuery("#firstview_wrapper").innerWidth();
    jQuery("ul#firstview li").each(function(){
      jQuery(this).css("width", w + 'px');
    });
    var all_w = w * jQuery("ul#firstview li").length;
    jQuery("ul#firstview").css({"width": all_w + 'px', "margin-left": (0 - w) + 'px'});
  }

  function firstview_action(){
    if(touch == 0){
      _action("auto");
    }
  }

  function _action(target){
    clearTimeout(firstview_action_timer);
    var ckpos = jQuery("#firstview_wrapper").offset().top + jQuery("#firstview_wrapper").innerHeight();
    if(jQuery(window, document, "html, body").scrollTop() <= ckpos){
      var w = jQuery("#firstview_wrapper").innerWidth();
      var next = 0 - w * 2;
      jQuery("a.firstview_arrow").each(function(){
        jQuery(this).addClass("untouch");
      });
      if(target == "left"){
        jQuery("ul#firstview li:last-child").prependTo("ul#firstview");
        jQuery("ul#firstview").css("margin-left", next + 'px');
        next = 0 - w;
      }
      jQuery("ul#firstview").stop().animate({"margin-left": next + 'px'}, "slow", function(){
        if(target != "left"){
          jQuery("ul#firstview li:first-child").appendTo("ul#firstview");
        }
        jQuery("ul#firstview li.active").removeClass("active");
        jQuery("ul#firstview li:nth-child(2)").addClass("active");
        jQuery("ul#firstview_controller li.active").removeClass("active");
        jQuery("ul#firstview_controller li#ct-" + jQuery("ul#firstview li.active").attr("id").split("-")[1]).addClass("active");
        if(target != "left"){
          jQuery("ul#firstview").css("margin-left", (0 - w) + 'px');
        }
        jQuery("a.firstview_arrow").each(function(){
          jQuery(this).removeClass("untouch");
        });
        if(tap == 1){
          tap_event_timer = setTimeout(function(){
            tap = 0;
            touch = 0;
          }, firstview_action_span * 0.5);
        }
        firstview_action_timer = setTimeout(function(){
          if(tap == 0){
            firstview_action();
          }
        }, firstview_action_span);
      });
    }else{
      firstview_action_timer = setTimeout(firstview_action, firstview_action_span);
    }
  }

  function firtview_direct(n){
    clearTimeout(firstview_action_timer);
    jQuery("ul#firstview").stop();
    jQuery("a.firstview_arrow").each(function(){
      jQuery(this).addClass("untouch");
    });
    var w = jQuery("#firstview_wrapper").innerWidth();
    var ml = parseInt(jQuery("ul#firstview").css("margin-left").replace(/px/, ""));
    var next = 0 - w;

    if(jQuery("ul#firstview li#fv-" + n).nextAll(".active").length > 0){
      if(jQuery("ul#firstview li#fv-" + n).prevAll().length < 1){
        jQuery("ul#firstview li:last-child").prependTo("ul#firstview");
        jQuery("ul#firstview").css("margin-left", (ml - w) + 'px');
      }
    }else{
      if(jQuery("ul#firstview li#fv-" + n).nextAll().length < 1){
        var dammy = jQuery("ul#firstview li:first-child").clone();
        jQuery("ul#firstview li:first-child").appendTo("ul#firstview");
        dammy.addClass("dammy").prependTo("ul#firstview");
        //jQuery("ul#firstview").css("margin-left", (ml + w) + 'px');
      }
      next = 0 - w * jQuery("ul#firstview li#fv-" + n).prevAll().length;
    }
    jQuery("ul#firstview").animate({"margin-left": next + 'px'}, "slow", function(){
      jQuery("ul#firstview li.active").removeClass("active");
      jQuery("ul#firstview li#fv-" + n).addClass("active");
      jQuery("ul#firstview li.dammy").remove();
      if(jQuery("ul#firstview li#fv-" + n).prevAll().length > 1){
        jQuery("ul#firstview li#fv-" + n).prev().prevAll().each(function(){
          jQuery(this).appendTo("ul#firstview");
        });
        jQuery("ul#firstview").css("margin-left", (0 - w) + 'px');
      }
      jQuery("ul#firstview_controller li.active").removeClass("active");
      jQuery("ul#firstview_controller li#ct-" + n).addClass("active");
      jQuery("a.firstview_arrow").each(function(){
        jQuery(this).removeClass("untouch");
      });
      firstview_action_timer = setTimeout(firstview_action, firstview_action_span);
    });
  }
});
