const searchWrapper = document.querySelector(".wrapper-ct-address");
const inputBox = searchWrapper.querySelector("input");
const listBox = searchWrapper.querySelector(".autocom-box");
const inputDataToDb = document.getElementById("userAddressInput");

$(document).ready(function(){
    $(inputBox).keyup(function(){

        var input  = $(this).val();
        var emptyArray = [];

        if (input != ""){            
            $.ajax({
                url:"u/address.php",
                method:"POST",
                data:{input:input},
                cache:false,
                success:function(data){
                    $(listBox).html(data);

                    emptyArray = getData.filter((data)=>{
                        return data.toLocaleLowerCase().startsWith(input.toLocaleLowerCase());
                    });
                    emptyArray = getData.map((data)=>{
                        return data = '<li>'+ data +'</li>';
                    });
                    searchWrapper.classList.add("active");
                    showSuggestions(emptyArray.slice(0, 5));
                    let allList = listBox.querySelectorAll("li");
                    for (let i = 0; i < allList.length; i++) {
                        allList[i].setAttribute("onclick", "select(this)");
                    }
                }
            });
        }else{
            searchWrapper.classList.remove("active");
        }
    });
});

document.addEventListener("keyup", e =>{
    if (e.key === "Escape") {
        window.location.replace("address");
    }
});

function select(element){
    let selectUserData = element.textContent;
    inputBox.value = selectUserData;
    inputDataToDb.value = selectUserData;
    searchWrapper.classList.remove("active");
}

function showSuggestions(list){
    let listData;
    if (!list.length) {
        userValue = inputBox.value;
        listData = '<li style="pointer-events: none;"><small>tidak ada hasil ditemukan untuk '+ userValue +'</small></li>';
    }else{
        listData = list.join('');
    }

    listBox.innerHTML = listData;
}