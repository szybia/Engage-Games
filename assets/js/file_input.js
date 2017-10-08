document.getElementById('file_input').onchange = function () {
  document.getElementById('photo_name').innerHTML = document.getElementById('file_input').value.replace("C:\\fakepath\\", "");
};
