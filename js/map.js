//map Initilizations
var sloc,endloc
var place1,place2
var olat,olng,dlat,dlng;
var map;
var map = new MapmyIndia.Map('map', {center: [28.09, 78.3], zoom: 5, search: false,zoomControl:false});
var map = new MapmyIndia.Map('map1', {center: [28.09, 78.3], zoom: 5, search: false,zoomControl:false});

//Search Plugin Intilization

var option_search={
    location:[28.65,77.22],
    //pod:'City',
    // distance:true,
    // fitbounds:false,
    // geolocation:true,
    // hyperlocal:true
}
    new MapmyIndia.search(document.getElementById("departure"),option_search,callback1)
    new MapmyIndia.search(document.getElementById("destination"),option_search,callback2)
    new MapmyIndia.search(document.getElementById("departure2"),option_search,callback1)
    new MapmyIndia.search(document.getElementById("destination2"),option_search,callback2) 



// creating functions
function callback1(data) { 
    if(data)
    {
        if(data.error)
        {
          
            console.warn(data.error);
            
        }
        else
        {
            var dt=data[0];
            if(!dt) return false;
            var eloc=dt.eLoc;
            olat=dt.latitude, olng=dt.longitude;
            sloc=eloc;                    
            place1=dt.placeName + (dt.placeAddress?", " + dt.placeAddress:"");
                
        }
    }
  }
  function callback2(data) { 
    if(data)
    {
        if(data.error)
        {
           
            console.warn(data.error);
            
        }
        else
        {
            var dt=data[0];
            if(!dt) return false;
            var eloc=dt.eLoc;
            dlat=dt.latitude,dlng=dt.longitude;
            endloc=eloc;                
            place2=dt.placeName + (dt.placeAddress?", "+dt.placeAddress:"");
        }
    }
  }
function calcRoute()
{
    /*direction plugin initialization*/
    var direction_option={
        map:map,
        start:{label:place1,geoposition:sloc},
        end:{label:place2,geoposition:endloc},
        //divId:'output',
       //divWidth:1500,
    callback:function(data)
    {
        console.log(data);
        
    }
    }
    var direction_plugin=MapmyIndia.direction(direction_option); 
}
map.on('click',function(e)
{
    calcRoute();
    map.setZoom(6)
})