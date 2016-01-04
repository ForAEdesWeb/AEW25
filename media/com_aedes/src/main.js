new WOW().init();

    var option = {
        address : [24.1554399, 120.6867363],
        addressText: "24.1554399, 120.6867363",
        label: '台中市北區三民路三段241-1號'
    };

    var AEdes = ({


        init: function(option)
        {
            this.scrollToContent();
            this.initContact();
            
            if (console)
            {
                if (console.log)
                {
                    console.log("別盯著人家看，我會害羞的。");
                }
            }

            return this;
        },

        initContact: function()
        {
            var thisClass = this;

            jQuery(document).ready(function($){
                option.address = [
                     parseFloat(jQuery("#address-position-x").html()),
                     parseFloat(jQuery("#address-position-y").html())
                ];

                option.addressText = jQuery("#address-position-x").html() + ", " + jQuery("#address-position-y").html();
                option.label = jQuery("#address-label").html();

                thisClass.loadContactForm($);
                thisClass.loadGoogleMap($);
            });
        },

        scrollToContent : function()
        {
            jQuery(function(){
                jQuery('a[href*=#]:not([href=#])').click(function()
                {
                    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
                        var target = jQuery(this.hash);
                        target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
                        if (target.length) {
                            jQuery('html,body').animate({
                                scrollTop: target.offset().top
                            }, 1000);
                            return false;
                        }
                    }
                });
            });
        },

        loadContactForm : function($)
        {
                $("#contact-form").html(
                    '<iframe src="' + $("#contact-form-src").html() + '"></iframe>'
                );
        },

        loadGoogleMap : function($)
        {
                // Gmap3
                $("#contact-map").gmap3({
                        map:{
                            options: {
                                center: option.address,//'臺北市松山區南京東路五段356號15樓之1',
                                zoom: 16,
                                mapTypeId: 'style2',
                                mapTypeControlOptions: {
                                    mapTypeIds: [google.maps.MapTypeId.ROADMAP, "style2"]
                                },
                                scrollwheel: false,
                                control: false,
                                mapTypeControl: false,
                                navigationControl: false,
                                scaleControl: false,
                                zoomControl: false,
                                disableDefaultUI: true
                            }
                        },
                        marker: {
                            values:[
                                {address: option.addressText, data: option.label}
                            ],
                            events:{
                                mouseover: function(marker, event, context){
                                    var map = $(this).gmap3("get"),
                                        infowindow = $(this).gmap3({get:{name:"infowindow"}});
                                    if (infowindow){
                                        infowindow.open(map, marker);
                                        infowindow.setContent(context.data);
                                    } else {
                                        $(this).gmap3({
                                            infowindow:{
                                                anchor:marker,
                                                options:{content: context.data}
                                            }
                                        });
                                    }
                                },
                                mouseout: function(){
                                    var infowindow = $(this).gmap3({get:{name:"infowindow"}});
                                    if (infowindow){
                                        infowindow.close();
                                    }
                                }
                            }
                        }
                    },
                    { styledmaptype:{
                        id: "style2",
                        options:{
                            name: "Style 2"
                        },
                        styles: [
                            {
                                stylers: [
                                    { saturation: -30 }
                                ]
                            },{
                                featureType: "road",
                                elementType: "geometry",
                                stylers: [
                                    { lightness: 100 },
                                    { visibility: "simplified" }
                                ]
                            },{
                                featureType: "road",
                                elementType: "labels",
                                stylers: [
                                    { visibility: "simplified" }
                                ]
                            }
                        ]
                    }
                    });
        }
    }).init(option);