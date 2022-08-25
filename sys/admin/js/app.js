const getTime = function(){
	document.getElementById("dgTime").innerHTML = new Date().toLocaleString("en-US",{timeZone:'Asia/Bangkok', timeStyle:'medium', hourCycle:'h23'});
}
getTime();
setInterval(getTime,1000);

const reload = document.getElementById('reload');
reload.addEventListener('click', reloadPage);
const productList = document.getElementById('product-list');
productList.addEventListener('click', productListPage);
const inputProduct = document.getElementById('add-product');
inputProduct.addEventListener('click', inputProductPage);

function loading(){
   var el = document.getElementById('post-data');
   el.innerHTML="<div class='loading-icon'><img src=\"https://jalansurga.info/wp-content/uploads/2018/02/loader-03.gif\"></div>";
}
function reloadPage(){
    window.location.replace("../admin/");
}

function productListPage(){
    const xhr = new XMLHttpRequest();

    xhr.onreadystatechange = function(){
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById('post-data').innerHTML = xhr.responseText;
        }else{
            loading();
        }
    }

    xhr.open('GET', 'page/product.php', true);
    xhr.send('?=edit');
}
function inputProductPage(){
    window.location.replace('input/');
}
