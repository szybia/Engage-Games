//Slider variables
var slider = document.getElementById("my_range");
var output = document.getElementById("slider_output");
var slider_2 = document.getElementById("my_range_2");
var output_2 = document.getElementById("slider_output_2");
//Set variables to default
output.innerHTML = slider.value;
output_2.innerHTML = slider_2.value;

//Set value when sliding
slider.oninput = function() {
    output.innerHTML = this.value;
}
slider_2.oninput = function() {
    output_2.innerHTML = this.value;
}
