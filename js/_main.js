let dataArray = [];

function loadFiles() {
  var UID = getUID();
  $.ajax({
    url: "PHP/main.php",
    method: "post",
    data: "loadFileTbl=" + UID,
  }).done(function (result) {
    console.log(result);
    result = JSON.parse(result);
    dataArray.length = 0;
    dataArray = result;
    //console.log(result);
    $("#userViewFilesTBL").empty();
    $("#userViewFilesTBL").append(
      //`MID`, `name`, `email`, `nic`, `age`, `phone`, `gender`
      "<thead><tr><th>File Name</th><th>Remark</th><th>Upload Date</th><th>Download Count</th><th>Action</th>"
    );
    result.forEach(function (result, index) {
      $("#userViewFilesTBL").append(
        '<tr><td>' +
        result.orginal_name +
        '</td><td>' +
        result.remark +
        '</td><td>' +
        result.timestamp +
        '</td><td>' +
        result.download_count +
        '</td><td><button type="button" class="btn-sm btn-danger"  onclick="rowClickHandle(' + index + ')">Download</button></td></td></tr>'
      );
    });
    $("#userViewFilesTBL").append("</tbody>");
  });
}

function loadFilesSearch(searchKey) {
  $.ajax({
    url: 'PHP/main.php',
    method: 'post',
    data: 'loadfileTblSearch=' + searchKey
  }).done(function (result, index) {
    console.log(result);
    result = JSON.parse(result);
    dataArray.length = 0;
    dataArray = result;
    //console.log(result);
    $("#userViewFilesTBL").empty();
    $("#userViewFilesTBL").append(
      "<thead><tr><th>File Name</th><th>Remark</th><th>Upload Date</th><th>Download Count</th><th>Action</th>"
    );
    result.forEach(function (result, index) {
      $("#userViewFilesTBL").append(
        '<tr><td>' +
        result.orginal_name +
        '</td><td>' +
        result.remark +
        '</td><td>' +
        result.timestamp +
        '</td><td>' +
        result.download_count +
        '</td><td><button type="button" class="btn-sm btn-danger"  onclick="rowClickHandle(' + index + ')">Download</button></td></td></tr>'
      );
    });
    $("#userViewFilesTBL").append("</tbody>");
  });
}

function rowClickHandle(index) {
  console.log(dataArray[index])
  row = dataArray[index];
  decryptFile(row.FID, row.file_code, row.pass_key)
}

$("form#addFileForm").on("submit", function (e) {
  console.log(e)
  e.preventDefault();
  var formData = new FormData(this);
  console.log(formData)
  $.ajax({
    url: "PHP/main.php",
    type: 'POST',
    data: formData,
    dataType: 'json',
    contentType: false,
    cache: false,
    processData: false,
  }).done(function (result) {
    updateProgressBar(33)
    console.log(result);
    console.log(result.FID);
    if (result.file_code && result.pass_key) {
      encryptFile(result.file_code, result.pass_key);
    }
  }).error(function (result) {
    console.log(result);
  });
});

function encryptFile(fileName, pass_key) {
  $.ajax({
    url: "PHP/main.php",
    method: "post",
    data: "encryptFile=" + fileName + "&pass_key=" + pass_key,
  }).done(function (result) {
    console.log(result.encryptedFileName);
    updateProgressBar(66)
  });
}

function uploadToCloud(fileName) {
  $.ajax({
    url: "PHP/main.php",
    method: "post",
    data: "encryptFile=" + fileName,
  }).done(function (result) {
    console.log(result.encryptedFileName);
    updateProgressBar(100)
  });
}

function downloadFormCloud(fileName) {
  $.ajax({
    url: "PHP/main.php",
    method: "post",
    data: "encryptFile=" + fileName,
  }).done(function (result) {
    console.log(result.encryptedFileName);
    updateProgressBar(33)
  });
}

function decryptFile(FID, fileName, pass_key) {
  $.ajax({
    url: "PHP/main.php",
    method: "post",
    data: "decryptFile=" + fileName + "&pass_key=" + pass_key + "&FID=" + FID,
  }).done(function (result) {
    result = JSON.parse(result);
    console.log(result)
    console.log(result.decryptedFileName);
    url = 'PHP/file_download.php?file_name=' +fileName
    download_to_pc(url,result.decryptedFileName)
    //updateProgressBar(66)
  });
}

function download_to_pc(fileUrl, fileName) {
  var a = document.createElement("a");
  a.href = fileUrl;
  a.setAttribute("download", fileName);
  a.click();
}

function updateProgressBar(current_progress) {
  console.log(current_progress + "% Complete");
  $("#dynamic")
    .css("width", current_progress + "%")
    .attr("aria-valuenow", current_progress)
    .text(current_progress + "% Complete");
}

function getUID() {
  if (localStorage.getItem("UID") === null) {
    return 1
  }
  return localStorage.getItem("UID");
}

function getUserName() {
  return localStorage.getItem("D_name");
}
