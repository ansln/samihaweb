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