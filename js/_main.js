function loadFiles() {
    var UID = getUID();
    $.ajax({
      url: "PHP/main.php",
      method: "post",
      data: "loadFileTbl=" + UID,
    }).done(function (result) {
      console.log(result);
      result = JSON.parse(result);
      //console.log(result);
      $("#userViewFilesTBL").empty();
      $("#userViewFilesTBL").append(
        //`MID`, `name`, `email`, `nic`, `age`, `phone`, `gender`
        "<thead><tr><th>File Name</th><th>Remark</th><th>Upload Date</th><th>Download Count</th><th>Action</th>"
      );
      result.forEach(function (result) {
        $("#userViewFilesTBL").append(
          '<tr><td>' +
          result.orginal_name +
          '</td><td>' +
          result.remark +
          '</td><td>' +
          result.timestamp +
          '</td><td>' +
          result.download_count +
          '</td><td><button type="button" class="btn btn-danger">Download</button></td></td></tr>'
        );
      });
      $("#userViewFilesTBL").append("</tbody>");
    });
  }

  $("form").on("submit",function(e){
     e.preventDefault();
     var formData = new FormData(this);
     console.log(formData)
     console.log(e)
     ajax({
         url: "PHP/main.php",
         type:'POST',
         data:formData,
         cache: false,
         contentType:false,
         processData:false
     }).done(function (result) {
        console.log(result);
        result = JSON.parse(result);
     });
});


  function getUID() {
    return localStorage.getItem("UID");
  }
  
  function getUserName() {
    return localStorage.getItem("D_name");
  }
  