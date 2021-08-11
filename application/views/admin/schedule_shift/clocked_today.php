<style type="text/css">
.table thead tr th {
    border: 1px solid rgba(0, 0, 0, 0.05) !important;
}

.table-bordered th, .table-bordered td {
    text-align: center;
}
.table-bordered th {
    width: 10%;
    background-color: #f1f1f1;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  /*padding: 8px 16px;*/
  z-index: 1;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown-content:hover{
    background-color: #f1f1f1;
}

</style>
<style>
    #map-canvas{
        width: 730px;/*468px;*/
        height: 550px;
    }
    .pac-container { z-index: 100000 !important; } 
    #searchmap{
        width: 320px;
        margin-top: 10px;
    }
</style>
<div class="page-wrapper">
	<div class="content container-fluid">
            <div class="row mb-4">
                <div class="col-sm-8">
                    <h4 class="page-title m-b-20 m-t-0">Clocked in Today</h4>
                </div>
            </div>
            <?php
            if ($this->session->userdata('message')) {
                echo $this->session->userdata('message');
            }
            ?>
            <div class="card pt-4">
                <div class="card-body">
                    <?php $attributes = array('id' => 'formDate');
                    echo form_open('admin/schedule_shift/clocked_today/', $attributes); ?>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <button name="form_submit" type="submit" id="search_btn" class="btn btn-primary center-block" value="true" style="display:none;">Search</button>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered categories_table">
                            <thead>
                                <tr>
                                    <th style="width:3%;">#</th>
                                    <th>Employee</th>
                                    <th>Status</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Total hours</th>
                                </tr>
                            </thead>
                                <?php
                                    $sno = 1;
                                    foreach ($list as $row) {
                                ?>
                                        <tr>
                                            <td><?php echo $sno ?></td>
                                            <td style="text-align:left;"><div><img class="avatar-sm rounded mr-1" src="<?php echo base_url().$row['user_profile_img']; ?>"><?php echo $row['user_name']; ?></div></td>
                                            <td><?php echo $row['status']; ?></td>
                                            <td style="cursor:pointer;">
                                                <div class="dropdown">
                                                    <span><?php echo $row['clock_in']; ?></span>
                                                    <?php if($row['clock_out'] == "--"){ ?>
                                                        <div class="dropdown-content" onclick="show_location('<?=$row['id']?>');">
                                                            <p class="mt-4">Show location</p>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <input type="hidden" id="user_<?=$row['id']?>_location" value="<?=$row['location']?>">
                                                <input type="hidden" id="user_<?=$row['id']?>_lat" value="<?=$row['latitude']?>">
                                                <input type="hidden" id="user_<?=$row['id']?>_long" value="<?=$row['longitude']?>">
                                                <input type="hidden" id="user_<?=$row['id']?>_e_lat" value="<?=$row['e_latitude']?>">
                                                <input type="hidden" id="user_<?=$row['id']?>_e_long" value="<?=$row['e_longitude']?>">
                                                <input type="hidden" id="user_<?=$row['id']?>_profile" value="<?=base_url().$row['user_profile_img']?>">
                                            </td>
                                            <td><?php echo $row['clock_out']; ?></td>
                                            <td><?php echo $row['total_hours']; ?></td>
                                        </tr>
                                <?php
                                        $sno ++;
                                    }
                                ?>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootbox.min.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAPAPI_KEY; ?>&callback=initAutocomplete&libraries=places&v=weekly" async></script>
<div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="width:770px;">
            <div class="modal-header">
                <span>Browse Map</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>                    
                </button>
            </div>
            <div class="modal-body" id="locationModalBody">
                <div>
                    <div class="row">
                        <div id="geofence_msg" class="col-md-12">
                            
                        </div>
                    </div>
                    <div class="form-group row">                        
                        <div class="col-md-12">
                            <div id="map-canvas"></div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <div class="row">
                            <div class="col-md-2"><label class="pt-3">Location</label></div>
                            <div class="col-md-10">
                                <!-- <input type="text" id="current_location" readonly="true" name="current_location" class="form-control"> -->
                            </div>
                        </div>
                        
                        <input type="hidden" id="current_lat" name="current_lat">
                        <input type="hidden" id="current_lng" name="current_lng">
                        <input type="hidden" id="current_e_lat" name="current_e_lat">
                        <input type="hidden" id="current_e_lng" name="current_e_lng">
                        <input type="hidden" id="profile_img" name="profile_img">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var radius = 5000;//5000m
function show_location(shift_tracking_id){
    // alert(shift_tracking_id);
    var location = $("#user_"+shift_tracking_id+"_location").val();
    var latitude = $("#user_"+shift_tracking_id+"_lat").val();
    var longitude = $("#user_"+shift_tracking_id+"_long").val();
    var e_latitude = $("#user_"+shift_tracking_id+"_e_lat").val();
    var e_longitude = $("#user_"+shift_tracking_id+"_e_long").val();
    var profile_img = $("#user_"+shift_tracking_id+"_profile").val();
    // $("#current_location").val(location);
    $("#current_lat").val(latitude);
    $("#current_lng").val(longitude);
    $("#current_e_lat").val(e_latitude);
    $("#current_e_lng").val(e_longitude);
    $("#profile_img").val(profile_img);

    var msg = "";
    if(e_latitude != "" && e_longitude !=""){
        var distance = calculateDistance(latitude, longitude, e_latitude, e_longitude);
        if(distance <= (radius/1000)){
            var diff_dis = Math.round(radius - distance*1000);
            msg += "<label style = 'color:#5c0cf7;font-size:20px;'>";
            msg += "Employee has entered a geofence.("+diff_dis+"m)";
            msg += "</label>";
        }else{
            var diff_dis = Math.round(distance*1000 - radius);
            msg += "<label style = 'color:#f70c0c;font-size:20px;'>";
            msg += "Employee has exited a geofence.("+diff_dis+"m)";
            msg += "</label>";
        }
    }else{
        msg += "<label style = 'color:#5c0cf7;font-size:20px;'>";
        msg += "Employee has dwelled a geofence.";
        msg += "</label>";
    }    
    $("#geofence_msg").html(msg);    
    initAutocomplete();
    $('#locationModal').modal("show");    
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    var R = radius; //m
    var dLat = convertRad(lat2-lat1);//(lat2-lat1).toRad();
    var dLon = convertRad(lon2-lon1);//(lon2-lon1).toRad();
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(convertRad(lat1)) * Math.cos(convertRad(lat2)) *
            Math.sin(dLon/2) * Math.sin(dLon/2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    var d = R * c;
    return d; //Km 
}

function convertRad(deg_val){
    return deg_val * Math.PI / 180;
}

function initAutocomplete() {
    // var radius = 5000; //5000m
    var zoom = 10;

    var init_lat = 0;//-33.8688;
    var init_lng= 0;//151.2195;
    if($("#current_lat").val() !=""){
        init_lat = 1*$("#current_lat").val();
    }    
    if($("#current_lng").val() !=""){
        init_lng = 1*$("#current_lng").val();
    }

    var employee_lat = init_lat;
    var employee_lng= init_lng;
    if($("#current_e_lat").val() !=""){
        employee_lat = 1*$("#current_e_lat").val();
    }    
    if($("#current_e_lng").val() !=""){
        employee_lng = 1*$("#current_e_lng").val();
    }

    const map = new google.maps.Map(document.getElementById("map-canvas"), {
        center: { lat: init_lat, lng: init_lng },
        zoom: zoom,
        mapTypeId: "roadmap",
    });

    var marker = new google.maps.Marker({
        position: {
            lat: init_lat,
            lng: init_lng
        },
        map: map,
        draggable: true
    });

    if(employee_lat != init_lat || employee_lng != init_lng){
        var img_width = 40;
        var img_height = 40;
        var icon = {
            url: $("#profile_img").val(), // url
            scaledSize: new google.maps.Size(img_width, img_height), // size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0,0) // anchor 
        };
        var employee_marker = new google.maps.Marker({
            position: {
                lat: employee_lat,
                lng: employee_lng
            },
            map: map,
            icon: icon,
            draggable: false
        });
    }        

    var circle = new google.maps.Circle({
        center: { lat: init_lat, lng: init_lng },
        map: map,
        radius: radius,          
        fillColor: '#FF6600',
        fillOpacity: 0.3,
        strokeColor: "#FFF",
        strokeWeight: 0,         
        editable: false
    });
    circle.bindTo('center', marker, 'position');

    // Create an info window to share between markers.
    const infoWindow = new google.maps.InfoWindow();   

    marker.addListener("position_changed", () => {
        infoWindow.open(marker.getMap(), marker);
    });
    marker.addListener("dragend", () => {
        geocoder = new google.maps.Geocoder();
        geocoder.geocode({latLng: marker.getPosition()}
            ,function(results, status){
                if (status == google.maps.GeocoderStatus.OK){
                    infoWindow.setContent(results[0].formatted_address);
                    // $("#current_location").val(results[0].formatted_address);
                } 
            }
        );

        var lat = marker.getPosition().lat();
        var lng = marker.getPosition().lng();
        $("#current_lat").val(lat);
        $("#current_lng").val(lng);
    });
}
</script>

