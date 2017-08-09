jQuery(document).ready(function() {

  smoothScroll();

  accordion();

  var slideMenu = new SlideMenu();
  slideMenu.init();
});

var mmUa = (function(u){
  return{
    Tablet:
    (/Windows/i.test(u) && /Touch/i.test(u)) ||
    /iPad/i.test(u) ||
    (/Android/i.test(u) && !/Mobile/i.test(u)) ||
    (/Firefox/i.test(u) && /Tablet/i.test(u)) ||
    /Kindle/i.test(u) ||
    /Silk/i.test(u) ||
    /Playbook/i.test(u),
    Mobile:
    (/Windows/i.test(u) && /Phone/i.test(u)) ||
    /iPhone/i.test(u) ||
    /iPod/i.test(u) ||
    (/Android/i.test(u) && /Mobile/i.test(u)) ||
    (/Firefox/i.test(u) && /Mobile/i.test(u)) ||
    /BlackBerry/i.test(u)
  };

})(window.navigator.userAgent);

var smoothScroll = function(){
  var scroll = jQuery('a.scroll');

  scroll.on('click', function(event){
    event.preventDefault();
    var speed = 600;
    var windowWidth = jQuery(window).width();

    var href= jQuery(this).attr("href");
    var target = jQuery(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top;
    if(windowWidth<769){
      var headerBlock = jQuery('.sp-content .headerInner').outerHeight();
      position -= headerBlock;
    }
    jQuery("html, body").animate({scrollTop:position}, speed, "swing");
    return false;
  });
};

var accordion = function(){
  var acdTitle = jQuery(".accordion-title");
  var acdInner = jQuery(".accordion-inner");
  var acdOpen = '-open';
  var acdException = '-exception';
  var acdLen = acdTitle.length;
  var orientationchangeFlg = false;

  jQuery(window).on("orientationchange", function(){
    orientationchangeFlg = true;
  });
  windowWidth = jQuery(window).width();
  acdInner.hide();
  if(windowWidth>768){
    for(i=0;i<acdLen;i++){
      if(acdTitle.eq(i).hasClass(acdException)){
        acdTitle.eq(i).addClass(acdOpen);
        acdInner.eq(i).show();
      }
    }
  }
};

var SlideMenu = function(){
  this.$wrapper = jQuery(".contentWrap");
  this.$menuBtn = jQuery(".spMenu").find(".menu-trigger");
  this.$slideMenu = jQuery(".sp-content .headerGlobalNavi");
  this.$yearSlideMenu = jQuery(".sp-content .sub-slide-inner");
  this.$menuInner = jQuery(".sp-content .headerGlobalNavi");
  this.$closeBtn = jQuery(".spMenu").find(".menu-trigger");
  this.$innerCloseBtn = this.$slideMenu.find(".closeBtn.sp-content");
  this.$contents = jQuery(".contentWrap");
  this.$headerInner = jQuery(".sp-content .headerInner");
  this.$paginationBtn = jQuery(".sp-content").find(".sub-menu-trigger");
  this.$yearCloseBtn = jQuery(".sp-content .closeBtn");
  this.$paginationInner = jQuery(".sub-slide-inner");
  this.$globalNavi = this.$slideMenu.find(".globalNavi");
  this.$globalNaviLink = this.$slideMenu.find(".globalNavi>li").find("a");
  this.$subNavi = this.$globalNavi.find(".subNavi");
  this.$searchBtn = jQuery(".sp-content").find(".search-trigger");
  this.$searchMenu = jQuery(".spSearchForm");
  this.data = {
    w: 0,
    duration: 300,
    easing: "linear",
    flg: false,
    minMenuH: this.$slideMenu.outerHeight(true),
    isOpen: false,
    yearOpen: false,
    subNaviIndex: null,
    actSubNavIndex: null,
    moveSpeed: 500,
    menuAnimateFlg: false,
    searchOpen: false
  };
};

SlideMenu.prototype = {
  init: function() {
    var self = this;
    self.data.minMenuH =  500;

    self.addEvent();
  },
  addEvent: function() {
    var self = this;

    self.$menuBtn.click(function(e){
      e.preventDefault();

      if(!self.data.flg && self.data.searchOpen) {
        self.searchCloseMenu();
      }else if(!self.data.flg && !self.data.isOpen){
        self.openMenu();
      }
    });

    self.$closeBtn.click(function(e){
      e.preventDefault();

      if(!self.data.flg && self.data.isOpen){
        self.closeMenu();
      }
    });

    self.$paginationBtn.click(function(e){
      e.preventDefault();

      if(!self.data.flg && self.data.searchOpen) {
        self.searchCloseYear();
      } else if(!self.data.flg && !self.data.yearOpen){
        self.yearOpenMenu();
      }
    });

    self.$yearCloseBtn.click(function(e){
      e.preventDefault();

      if(!self.data.flg && self.data.yearOpen){
        self.yearCloseMenu();
      }
    });

    self.$innerCloseBtn.click(function(e){
      e.preventDefault();

      if(!self.data.flg && self.data.isOpen){
        self.closeMenu();
      }
    });

    self.$searchBtn.click(function(e){
      e.preventDefault();

      if(!self.data.flg && self.data.isOpen){
        self.changeSearch();
      } else if(!self.data.flg && self.data.yearOpen) {
        self.changeSearch();
      } else if(!self.data.flg && self.data.searchOpen) {
        self.searchClose();
      } else {
        self.searchOpen();
      }
    });

    self.$subNavi.prev("a").click(function(e){
      e.preventDefault();

      if(!self.data.menuAnimateFlg){
        self.data.menuAnimateFlg = true;
        self.data.subNaviIndex = self.$subNavi.prev("a").index(this);

        self.accordionMenu();
      }
    });

  },
  openMenu: function() {
    var self = this;

    self.data.flg = true;
    self.data.isOpen = true;
    self.data.yearOpen = false;
    self.$slideMenu.css({"display": "block"});
    self.data.w = jQuery(window).width() * 0.8;
    self.$contents.animate({
      left: "-260px"
    }, {
      "duration": self.data.duration,
      "easing": self.data.easing,
      "complete": function(){
        self.data.flg = false;
      },
      "progress": function(anim, progress, fx){
        if(self.$contents.offset().left < 0){
          self.$paginationInner.css({"display": "none"});
        }
      }
    });
    self.$menuBtn.addClass('active');
    self.$paginationBtn.removeClass('active');
  },
  closeMenu: function() {
    var self = this;

    self.data.flg = true;
    self.$contents.animate({
      left: "0px"
    }, {
      "duration": self.data.duration,
      "easing": self.data.easing,
      "complete": function(){
        self.data.flg = false;
        self.data.isOpen = false;
        self.$slideMenu.css({"display": "none"});
      }
    });
    self.$menuBtn.removeClass('active');
  },
  yearOpenMenu: function() {
    var self = this;

    self.data.flg = true;
    self.data.yearOpen = true;
    self.data.isOpen = false;
    self.$yearSlideMenu.css({"display": "block"});
    self.data.w = jQuery(window).width() * 0.8;
    self.$contents.animate({
      left: "260px"
    }, {
      "duration": self.data.duration,
      "easing": self.data.easing,
      "complete": function(){
        self.data.flg = false;
      },
      "progress": function(anim, progress, fx){
        if(self.$contents.offset().left > 0){
          self.$menuInner.css({"display": "none"});
        }
      }
    });
    self.$menuBtn.removeClass('active');
    self.$paginationBtn.addClass('active');
  },
  yearCloseMenu: function() {
    var self = this;

    self.data.flg = true;
    self.$contents.animate({
      left: "0px"
    }, {
      "duration": self.data.duration,
      "easing": self.data.easing,
      "complete": function(){
        self.data.flg = false;
        self.data.yearOpen = false;
        self.$yearSlideMenu.css({"display": "none"});
      }
    });
    self.$paginationBtn.removeClass('active');
  },
  accordionMenu: function() {
    var self = this;

    self.$globalNaviLink.removeClass("-open");

    if(self.data.actSubNavIndex == self.data.subNaviIndex){
      self.$subNavi.slideUp(self.data.moveSpeed, function(){
        self.data.actSubNavIndex = null;
        self.data.menuAnimateFlg = false;
      });
    }else{
      self.$subNavi.not(self.$subNavi.eq(self.data.subNaviIndex)).slideUp(self.data.moveSpeed);
      self.$subNavi.prev("a").eq(self.data.subNaviIndex).addClass("-open");
      self.$subNavi.eq(self.data.subNaviIndex).slideDown(self.data.moveSpeed, function(){
        self.data.actSubNavIndex = self.data.subNaviIndex;
        self.data.menuAnimateFlg = false;
      });
    }

  },
  searchOpen: function() {
    var self = this;

    self.$searchMenu.slideDown(300,function(){
      self.data.searchOpen = true;
    });
  },
  searchClose: function() {
    var self = this;

    self.$searchMenu.slideUp(300,function(){
      self.data.searchOpen = false;
    });
  },
  changeSearch: function() {
    var self = this;

    self.data.flg = true;
    self.$contents.animate({
      left: "0px"
    }, {
      "duration": self.data.duration,
      "easing": self.data.easing,
      "complete": function(){
        self.data.flg = false;
        self.data.isOpen = false;
        self.data.yearOpen = false;
        self.$slideMenu.css({"display": "none"});
        self.$searchMenu.slideDown(300,function(){
          self.data.searchOpen = true;
        });
      }
    });
    self.$menuBtn.removeClass('active');
    self.$paginationBtn.removeClass('active');
  },
  searchCloseMenu: function() {
    var self = this;

    self.$searchMenu.slideUp(300,function(){
      self.data.searchOpen = false;
      self.data.flg = true;
      self.data.isOpen = true;
      self.$slideMenu.css({"display": "block"});
      self.data.w = jQuery(window).width() * 0.8;
      self.$contents.animate({
        left: "-260px"
      }, {
        "duration": self.data.duration,
        "easing": self.data.easing,
        "complete": function(){
          self.data.flg = false;
        },
        "progress": function(anim, progress, fx){
          if(self.$contents.offset().left < 0){
            self.$paginationInner.css({"display": "none"});
          }
        }
      });
      self.$menuBtn.addClass('active');
    });
  },
  searchCloseYear: function() {
    var self = this;

    self.$searchMenu.slideUp(300,function(){
      self.data.searchOpen = false;
      self.data.flg = true;
      self.data.yearOpen = true;
      self.data.isOpen = false;
      self.$yearSlideMenu.css({"display": "block"});
      self.data.w = jQuery(window).width() * 0.8;
      self.$contents.animate({
        left: "260px"
      }, {
        "duration": self.data.duration,
        "easing": self.data.easing,
        "complete": function(){
          self.data.flg = false;
        },
        "progress": function(anim, progress, fx){
          if(self.$contents.offset().left > 0){
            self.$menuInner.css({"display": "none"});
          }
        }
      });
      self.$menuBtn.removeClass('active');
      self.$paginationBtn.addClass('active');
    });
  }
};