const wrapper = document.querySelector(".wrapper"),
selectBtn = wrapper.querySelector(".select-btn"),
options = wrapper.querySelector(".options");
var items = document.querySelectorAll("#shippingOptions");

function optionsSelect(selectedOptions){
    for (var i = 0; i < items.length; i++) {
        items[i].onclick = function(){
            let result = document.getElementById("show").value = this.innerText;
        }
    }
}
optionsSelect();

function updateSelect(selectedLi){
    optionsSelect(selectedLi.innerText);

    wrapper.classList.remove("active");
    selectBtn.firstElementChild.innerText = selectedLi.innerText;

    // PUSH TO INPUT
    const inputShipping = document.getElementById("getShippingSelect");
    inputShipping.value = selectedLi.innerText;
}

selectBtn.addEventListener("click", () => {
    wrapper.classList.toggle("active");
});