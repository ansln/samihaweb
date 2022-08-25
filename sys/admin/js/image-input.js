const image_input1 = document.querySelector("#image_input1");
const image_input2 = document.querySelector("#image_input2");
const image_input3 = document.querySelector("#image_input3");
const image_input4 = document.querySelector("#image_input4");
const image_input5 = document.querySelector("#image_input5");
var uploaded_image = "";

image_input1.addEventListener("change", function(){
    const reader = new FileReader();
    reader.addEventListener("load", () => {
        uploaded_image = reader.result;
        document.querySelector("#display_image1").style.backgroundImage = `url(${uploaded_image})`
        document.querySelector("#display_image1").style.border = "none";
        document.querySelector("#upload-icon1").style.display = "none";
    });
    reader.readAsDataURL(this.files[0]);
});
image_input2.addEventListener("change", function(){
    const reader = new FileReader();
    reader.addEventListener("load", () => {
        uploaded_image = reader.result;
        document.querySelector("#display_image2").style.backgroundImage = `url(${uploaded_image})`
        document.querySelector("#display_image2").style.border = "none";
        document.querySelector("#upload-icon2").style.display = "none";
    });
    reader.readAsDataURL(this.files[0]);
});
image_input3.addEventListener("change", function(){
    const reader = new FileReader();
    reader.addEventListener("load", () => {
        uploaded_image = reader.result;
        document.querySelector("#display_image3").style.backgroundImage = `url(${uploaded_image})`
        document.querySelector("#display_image3").style.border = "none";
        document.querySelector("#upload-icon3").style.display = "none";
    });
    reader.readAsDataURL(this.files[0]);
});
image_input4.addEventListener("change", function(){
    const reader = new FileReader();
    reader.addEventListener("load", () => {
        uploaded_image = reader.result;
        document.querySelector("#display_image4").style.backgroundImage = `url(${uploaded_image})`
        document.querySelector("#display_image4").style.border = "none";
        document.querySelector("#upload-icon4").style.display = "none";
    });
    reader.readAsDataURL(this.files[0]);
});
image_input5.addEventListener("change", function(){
    const reader = new FileReader();
    reader.addEventListener("load", () => {
        uploaded_image = reader.result;
        document.querySelector("#display_image5").style.backgroundImage = `url(${uploaded_image})`
        document.querySelector("#display_image5").style.border = "none";
        document.querySelector("#upload-icon5").style.display = "none";
    });
    reader.readAsDataURL(this.files[0]);
});