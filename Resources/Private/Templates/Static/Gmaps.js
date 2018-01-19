$(document).ready(function() {    
           
    var initializeMap = function() {

    	$("#map-canvas").gmap3({
			map: {
				options: {
					center: [{center.lat}, {center.long}],
					zoom: {zoom},
                    draggable: true,
					mapTypeId: google.maps.MapTypeId.ROADMAP,
//					mapTypeControlOptions: {
//          				 mapTypeIds: [google.maps.MapTypeId.ROADMAP, "blau"]
//        			},
					panControl: false,
					zoomControl: true,
					zoomControlOptions: {
						style: google.maps.ZoomControlStyle.SMALL,
						position: google.maps.ControlPosition.TOP_LEFT
					},
					scaleControl: false,
					streetViewControl: false,
					isableDoubleClickZoom: true,
				}
			},

			marker: {
				values: [
				<f:for each="{locations}" as="point"> 
					<![CDATA[{]]>latLng:[{point.lat},{point.long}],data:"{point.data->f:format.raw()}",id:{point.id}}, 
				</f:for>
				],
				options:{
					draggable: false
				},
				events:{
					{markeronevent}: function(marker, event, context){
						updateMarker(context.id);
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
					{markeroffevent}: function(){
						var infowindow = $(this).gmap3({get:{name:"infowindow"}});
						if (infowindow){
							infowindow.close();
						}
					},
					//click: function (marker, event, context) {}
				}
			}
		});
    }
    
    $('.toggle-button').click(function(e) {
        initializeMap();
    });
    
    function updateMarker(id) {
        $("#map-canvas").gmap3({
            exec: {
                name: "marker",
                all:  "true",
                func: function(data){
                    // data.object is the google.maps.Marker object
                    if (data.id === id) {
                        data.object.setIcon("http://maps.google.com/mapfiles/marker_green.png");
                    } else {
                        data.object.setIcon("http://maps.google.com/mapfiles/marker.png");
                    }
                }
                
            }
        });
    }

    $(".marker").hover(function() <![CDATA[{]]>
        var uid = $(this).data("uid");
        var marker = $("#map-canvas").gmap3({
            get:{name: "marker", id: uid}
        });
        google.maps.event.trigger(marker, "{markeronevent}")
        return false;
    });
});
