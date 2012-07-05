<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

	google.maps.event.addDomListener(window, 'load', function(){

	var markerObj;//初期マーカー
	var mapObj;//マップ定義
	var defmaplng = 135.5022570490837;//初期緯度
	var defmaplat = 34.69365603896239;//初期軽度
	var defmapzoom = 16;//初期ズーム
	var defmaplatlng = new google.maps.LatLng(defmaplat, defmaplng);//緯度経度取得
	var image = 'http://maps.google.co.jp/mapfiles/ms/icons/blue.png';//マーカーON
	var imageoff = 'http://maps.google.co.jp/mapfiles/ms/icons/orange.png';//マーカーオフ
	var CurrentMarkerNo = -1;//現在選択中のマーカー
	var markerClickAlready = 0; //既存マーカークリック判別
	
//zoom9以下表示しない
//ストリートビューは表示しない

	var mapOptions = {//初期マップオプション
		zoom: defmapzoom,
		center: defmaplatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		scaleControl: true,
		streetViewControl: false//ストリートビューオフ
	};
	mapObj = new google.maps.Map($("#gmap")[0], mapOptions);

	   //zoomsize変更
    google.maps.event.addListenerOnce(mapObj, "projection_changed", function(){
      mapObj.setMapTypeId(google.maps.MapTypeId.HYBRID);  //一瞬だけマップタイプを変更
      setZoomLimit(mapObj, google.maps.MapTypeId.ROADMAP);
      setZoomLimit(mapObj, google.maps.MapTypeId.HYBRID);
      setZoomLimit(mapObj, google.maps.MapTypeId.SATELLITE);
      setZoomLimit(mapObj, google.maps.MapTypeId.TERRAIN);
      mapObj.setMapTypeId(google.maps.MapTypeId.ROADMAP);  //もとに戻す
    });
 
  function setZoomLimit(map, mapTypeId){
    //マップタイプIDを管理するレジストリを取得
    var mapTypeRegistry = map.mapTypes;
    
    //レジストリから現在のマップタイプIDのMapTypeを取得する
    var mapType = mapTypeRegistry.get(mapTypeId);
    //ズームレベルを設定する
    mapType.maxZoom = 18;  //MAXZOOM
    mapType.minZoom = 9;　　//MINZOOM
  }

	//初期マーカー指定
	markerObj = new google.maps.Marker({
		position: defmaplatlng,
		draggable: true,
		map: mapObj,
		icon: image
	});

	// マップクリックイベントを追加
	google.maps.event.addListener(mapObj, 'click', function(e){
	// ポジションを変更
		markerObj.position = e.latLng;
	// マーカーをセット
		markerObj.setMap(mapObj);
		$("#lat").text(e.latLng.lat());
		$("#lng").text(e.latLng.lng());
		$("#content").text("");
		
	    markerObj.setIcon(image);
	    if (CurrentMarkerNo >= 0) {
	        myMarker[CurrentMarkerNo].setIcon(imageoff);
	    	CurrentMarkerNo = -1;
	    	
		}
	   	    
		
	});
	//移動可能マーカーをクリックした時の動作
	google.maps.event.addListener(markerObj, 'click', function(e){
		$("#lat").text(e.latLng.lat());
		$("#lng").text(e.latLng.lng());
		$("#content").text("");
		
	    markerObj.setIcon(image);
	    if (CurrentMarkerNo >= 0) {
	        myMarker[CurrentMarkerNo].setIcon(imageoff);
	    	CurrentMarkerNo = -1;
	    	
		}		
	});
	
	// マーカードラッグ中のイベントを追加
	google.maps.event.addListener(markerObj, 'drag', function(e){
		$("#lat").text(e.latLng.lat());
		$("#lng").text(e.latLng.lng());
		$("#content").text("");
		
	    markerObj.setIcon(image);
	    if (CurrentMarkerNo >= 0) {
	        myMarker[CurrentMarkerNo].setIcon(imageoff);
	    	CurrentMarkerNo = -1;
	    	
		}		
	});

	// 交差点データ
	  var data = new Array();
	  data.push({position: new google.maps.LatLng(34.69644353066994,135.5013558268547), content: '交差点１'});
	  data.push({position: new google.maps.LatLng(34.697960733532376,135.5020210146904), content: '交差点2'});
	  data.push({position: new google.maps.LatLng(34.698031300430415,135.50051897764206), content: '交差点3'});


		var myMarker = new Array;

	  for (i = 0; i < data.length; i++) {
			  
	    myMarker[i] = new google.maps.Marker({
	      position: data[i].position,
	      map: mapObj,
	      icon: imageoff
	    });
	    
	    changeMark(myMarker[i], data[i].latlng,data[i].content,i);
	    
	  }



//マーカーチェンジ
	  function changeMark(marker, latlng,content,no) {
			  
		    google.maps.event.addListener(marker, 'click', function(e) {
		    	if(markerClickAlready != 1){
					$("#lat").text(e.latLng.lat());
					$("#lng").text(e.latLng.lng());
					$("#content").text(content);
					
				    marker.setIcon(image);
				    markerObj.setIcon(imageoff);
				    
				     //前回の選択マーカーの画像を元に戻す
				     if (CurrentMarkerNo >= 0 && no != CurrentMarkerNo) {
				        myMarker[CurrentMarkerNo].setIcon(imageoff);
				     }
				    CurrentMarkerNo = no; //選択したマーカーの配列番号をグローバル変数にセット
				    markerClickAlready = 1; //マーカークリック判定
		    	}
				  
				
			    
		    });
		  }

});
	
</script>
</head>
<body>
    <div id="gmap" style="width: 600px; height: 370px; border: 1px solid Gray;">
    </div>
    緯度：<span id="lat"></span><br />
    経度：<span id="lng"></span><br />
    マーカーID?：<span id="content"></span><br />    
</body>
</html>
