let dataArray = [];

function updateStepStatus(step, val) {
  let element;
  switch (step) {
    case 1:
      element = document.getElementById("step1");
      break;
    case 2:
      element = document.getElementById("step2");
      break;
    case 3:
      element = document.getElementById("step3");
      break;
  }
  if (val) {
    element.classList.remove("disabled");
    element.classList.add("completed");
  } else {
    element.classList.remove("completed");
    element.classList.add("disabled");
  }
}

function getStatistics(){
  var UID = getUID();
  $.ajax({
    url: "PHP/main.php",
    method: "post",
    data: "getStatistics=" + UID,
  }).done(function (result) {
    console.log(result);
    result = JSON.parse(result);
    document.getElementById("upload_count").textContent = result.upload_count;
    document.getElementById("download_count").textContent = result.download_count;
  });
}

function loadRecentFiles() {
  Spinner.show();
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
    $("#userViewRecentFilesTBL").empty();
    $("#userViewRecentFilesTBL").append(
      //`MID`, `name`, `email`, `nic`, `age`, `phone`, `gender`
      "<thead><tr><th>File Name</th><th>Remark</th><th>Upload Date</th><th>Download Count</th>"
    );
    result.forEach(function (result, index) {
      if (index > 5) {
        return;
      }
      $("#userViewRecentFilesTBL").append(
        '<tr><td>' +
        result.orginal_name +
        '</td><td>' +
        result.remark +
        '</td><td>' +
        result.timestamp +
        '</td><td>' +
        result.download_count +
        '</td></tr>'
      );
    });
    $("#userViewRecentFilesTBL").append("</tbody>");
    Spinner.hide();
  });
}

function loadFiles() {
  Spinner.show();
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
        '</td><td><button type="button" class="btn-sm btn-danger"  onclick="rowClickHandle(' + index + ')">Download</button></td></tr>'
      );
    });
    $("#userViewFilesTBL").append("</tbody>");
    Spinner.hide();
  });
}

function loadFilesSearch(searchKey) {
  $.ajax({
    url: 'PHP/main.php',
    method: 'post',
    data: 'loadfileTblSearch=' + searchKey
  }).done(function (result) {
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
        '</td><td><button type="button" class="btn-sm btn-danger"  onclick="rowClickHandle(' + index + ')">Download</button></td></tr>'
      );
    });
    $("#userViewFilesTBL").append("</tbody>");
  });
}

function rowClickHandle(index) {
  Spinner.show();
  console.log(dataArray[index])
  row = dataArray[index];
  downloadFormCloud(row.FID, row.file_code, row.pass_key)
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
    updateProgressBar(33);
    updateStepStatus(1, true);
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
    console.log(result);
    result = JSON.parse(result);
    console.log(result);
    console.log(result.encryptedFileName);
    uploadToCloud(result.encryptedFileName)
    updateProgressBar(66);
    updateStepStatus(2, true);
  });
}

function uploadToCloud(fileName) {
  $.ajax({
    url: "PHP/main.php",
    method: "post",
    data: "addFileToCloud=" + fileName,
  }).done(function (result) {
    console.log(result.encryptedFileName);
    updateProgressBar(100);
    updateStepStatus(3, true);
  });
}

function downloadFormCloud(FID, file_code, pass_key) {
  $.ajax({
    url: "PHP/main.php",
    method: "post",
    data: "getFileFromCloud=" + file_code + "&FID=" + FID,
  }).done(function (result) {
    result = JSON.parse(result);
    console.log(result.encryptedCloudFileName);
    decryptFile(FID, file_code, pass_key)
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
    url = 'PHP/file_download.php?file_name=' + fileName
    download_to_pc(url, result.decryptedFileName)
  });
}

function download_to_pc(fileUrl, fileName) {
  var a = document.createElement("a");
  a.href = fileUrl;
  a.setAttribute("download", fileName);
  a.click();
  loadFiles();
  Spinner.hide();
}

function updateProgressBar(current_progress) {
  console.log(current_progress + "% Complete");
  $("#dynamic")
    .css("width", current_progress + "%")
    .attr("aria-valuenow", current_progress)
    .text(current_progress + "% Complete");
}

function getUID() {
  if(localStorage.getItem("UID") === null){
    alert("Please login first")
    window.location.href = "ecp_login.html"
  }
  return localStorage.getItem("UID");
}

function getUserName() {
  return localStorage.getItem("Name");
}

