function getId(value){ return document.getElementById(value); }
function getClass(value){ return document.getElementsByClassName(value); }

const wrapper = document.querySelector(".wrapper"),
selectBtn = wrapper.querySelector(".select-btn"),
options = wrapper.querySelector(".options");

let gender = ["Pria", "Wanita"];

function addGender(selectedGender){
    options.innerHTML = "";
    gender.forEach(optGender=>{
        let li = `<li onclick="updateName(this)" name="genderSelect" value="${optGender}">${optGender}</li>`;
        options.insertAdjacentHTML("beforeend", li);
    });
}
addGender();

function updateName(selectedLi){
    addGender(selectedLi.innerText);

    wrapper.classList.remove("active");
    selectBtn.firstElementChild.innerText = selectedLi.innerText;

    // PUSH TO INPUT
    const inputGender = document.getElementById("getGenderSelect");
    inputGender.value = selectedLi.innerText;
}

selectBtn.addEventListener("click", () => {
    wrapper.classList.toggle("active");
});

$(document).ready(function() {
    $("#login-link").click(function(){
        window.location.replace("login.php")
    });
    $(".reg-fill").click(function(){
        $("#infoMsg").fadeOut("slow");
    });
    $("#title-img").click(function(){
        window.location.replace("./");
    });
});

// password validation
getClass("toggle-password")[0].addEventListener("click", function(){
    getClass("toggle-password")[0].classList.toggle("active");
    if (getId("user-password").getAttribute("type") == "password") {
        getId("user-password").setAttribute("type", "text");
    }else{
        getId("user-password").setAttribute("type", "password");
    }
});
getId("user-password").addEventListener("focus", function(){
    getClass("password-info")[0].style.display = "flex";
});
getId("user-password").addEventListener("blur", function(){
    getClass("password-info")[0].style.display = "none";
});

getId("user-password").addEventListener("keyup", function(){
    const passwordValue = getId("user-password").value;

    if (/[A-Z]/.test(passwordValue)) {
        getClass("password-info-text")[2].classList.add("active");
        getClass("fa-check")[2].style.display = "block";
    }else{
        getClass("password-info-text")[2].classList.remove("active");
        getClass("fa-check")[2].style.display = "none";
    }

    if (/[0-9]/.test(passwordValue)) {
        getClass("password-info-text")[1].classList.add("active");
        getClass("fa-check")[1].style.display = "block";
    }else{
        getClass("password-info-text")[1].classList.remove("active");
        getClass("fa-check")[1].style.display = "none";
    }

    if (/[^A-Za-z0-9]/.test(passwordValue)) {
        getClass("password-info-text")[3].classList.add("active");
        getClass("fa-check")[3].style.display = "block";
    }else{
        getClass("password-info-text")[3].classList.remove("active");
        getClass("fa-check")[3].style.display = "none";
    }
    
    if (passwordValue.length > 7) {
        getClass("password-info-text")[0].classList.add("active");
        getClass("fa-check")[0].style.display = "block";
    }else{
        getClass("password-info-text")[0].classList.remove("active");
        getClass("fa-check")[0].style.display = "none";
    }
});