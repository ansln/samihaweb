function province_select(str){
    if (window.XMLHttpRequest) {
        xmlhttp = new XMLHttpRequest();
    }else{
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function(){
        if (this.readyState==4 && this.status==200) {
            document.getElementById('selectCity').innerHTML = this.responseText;
        }
    }
    xmlhttp.open("GET", "ongkir/curl_cty.php?province="+str, true);
    xmlhttp.send();
}