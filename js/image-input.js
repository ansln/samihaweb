const image_input1 = document.querySelector("#file");
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