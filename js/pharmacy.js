function loadPrescription() {
  var ph_ID = getPharmacyID();
  $.ajax({
    url: "PHP/pharmacy.php",
    method: "post",
    data: "loadPrescription=" + ph_ID,
  }).done(function (result) {
    //console.log(result);
    result = JSON.parse(result);
    //console.log(result);
    $("#pharmacyViewPrescriptionTBL").empty();
    $("#pharmacyViewPrescriptionTBL").append(
      //`Pre_ID`, `Pre_Date`, `QR_ID`
      "<thead><tr><th>Queue ID</th><th>Name</th><th>Prescription Date</th><th>Queue log</th>"
    );
    result.forEach(function (result) {
      $("#pharmacyViewPrescriptionTBL").append(
        '<tr  onClick="loadPresciptionItems(' +
          result.Pre_ID +
          ')"><td>' +
          result.PQID +
          "</td><td>" +
          result.name +
          "</td><td>" +
          result.Pre_Date +
          "</td><td>" +
          result.timestamp +
          "</td></tr>"
      );
    });
    $("#pharmacyViewPrescriptionTBL").append("</tbody>");
  });
}

function loadPresciptionItems(pre_id) {
  console.log(pre_id);
  document.getElementById("checkoutBtn").value = pre_id
  $.ajax({
    url: "PHP/doctor.php",
    method: "post",
    data: "loadPresciptionItems=" + pre_id,
  }).done(function (result) {
    //console.log(result);
    result = JSON.parse(result);
    console.log(result);

    $("#tblDrg").empty();

    result.forEach(function (result) {
      $("#tblDrg").append(
        "<tr><td>" +
          result.drg_ID +
          "</td><td>" +
          result.drg_name +
          "</td><td>" +
          result.manf_comp +
          "</td><td>" +
          result.drg_strength +
          "</td><td>" +
          result.dose +
          "</td></tr>"
      );
    });

    modal.style.display = "block";
  });
}

function loadPharmacy() {
  var ph_ID = getPharmacyID();
  $.ajax({
    url: "PHP/pharmacy.php",
    method: "post",
    data: "loadPharmacy=" + ph_ID,
  }).done(function (result) {
    //console.log(result);
    result = JSON.parse(result);
    console.log(result);
    document.getElementById("PHID").value = result[0].ph_ID;
    document.getElementById("txtPharName").value = result[0].Ph_name;
    document.getElementById("txtPharEmail").value = result[0].ph_email;
    document.getElementById("txtPharRegId").value = result[0].Ph_reg;
    document.getElementById("txtPharLocation").value = result[0].location;
    document.getElementById("txtPharPhone").value = result[0].phone;
    generateQR(result[0].QRID);
  });
}

function getPharmacyID() {
  return localStorage.getItem("ph_ID");
}

function getUserName() {
  return localStorage.getItem("Ph_name");
}
