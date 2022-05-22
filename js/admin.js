function loadPharmacies() {
  $.ajax({
    url: "PHP/admin.php",
    method: "post",
    data: "loadPhamacy=" + 5,
  }).done(function (result) {
    console.log(result);
    result = JSON.parse(result);
    console.log(result);
    $("#adminViewPhamacyTBL").empty();
    $("#adminViewPhamacyTBL").append(
      //`ph_ID`, `Ph_name`, `Ph_reg`, `LID`, `location`, `phone`
      "<thead><th>Phamacy ID</th><th>Name</th><th>Reg No</th><th>Email</th><th>Location</th><th>Phone</th></thead><tbody id='adminViewPhamacyTBody'>"
    );
    result.forEach(function (result) {
      $("#adminViewPhamacyTBL").append(
        "<tr><td>" +
          result.ph_ID +
          "</td><td>" +
          result.Ph_name +
          "</td><td>" +
          result.Ph_reg +
          "</td><td>" +
          result.ph_email +
          "</td><td>" +
          result.location +
          "</td><td>" +
          result.phone +
          "</td></tr>"
      );
    });
    $("#adminViewPhamacyTBL").append("</tbody>");
  });
}

function loadDoctors() {
  $.ajax({
    url: "PHP/admin.php",
    method: "post",
    data: "loadDoctor=" + 5,
  }).done(function (result) {
    console.log(result);
    result = JSON.parse(result);
    //console.log(result);
    $("#adminViewDoctorTBL").empty();
    $("#adminViewDoctorTBL").append(
      //`DID`, `D_name`, `phone`, `medicalRegID`, `nic`, `email
      "<thead><th>Doctor ID</th><th>Name</th><th>Phone No</th><th>Reg No</th><th>NIC</th><th>Email</th><tbody id='adminViewDoctoTBody'>"
    );
    result.forEach(function (result) {
      $("#adminViewDoctorTBL").append(
        "<tr><td>" +
          result.DID +
          "</td><td>" +
          result.D_name +
          "</td><td>" +
          result.phone +
          "</td><td>" +
          result.medicalRegID +
          "</td><td>" +
          result.nic +
          "</td><td>" +
          result.email +
          "</td></tr>"
      );
    });
    $("#adminViewDoctorTBL").append("</tbody>");
  });
}

function loadMembers() {
  $.ajax({
    url: "PHP/admin.php",
    method: "post",
    data: "loadPatient=" + 5,
  }).done(function (result) {
    console.log(result);
    result = JSON.parse(result);
    //console.log(result);
    $("#adminViewPatientTBL").empty();
    $("#adminViewPatientTBL").append(
      //`MID`, `name`, `email`, `nic`, `age`, `phone`, `gender`
      "<thead><th>Patient ID</th><th>Name</th><th>Email</th><th>NIC</th><th>Age</th><th>Phone</th><th>Gender</th><tbody id='adminViewPatientTBody'>"
    );
    result.forEach(function (result) {
      $("#adminViewPatientTBL").append(
        "<tr><td>" +
          result.MID +
          "</td><td>" +
          result.name +
          "</td><td>" +
          result.email +
          "</td><td>" +
          result.nic +
          "</td><td>" +
          result.age +
          "</td><td>" +
          result.phone +
          "</td><td>" +
          result.gender +
          "</td></tr>"
      );
    });
    $("#adminViewPatientTBL").append("</tbody>");
  });
}

function loadDrugs() {
  $.ajax({
    url: "PHP/admin.php",
    method: "post",
    data: "loadDrug=" + 5,
  }).done(function (result) {
    console.log(result);
    result = JSON.parse(result);
    //console.log(result);
    $("#adminViewDrugTBL").empty();
    $("#adminViewDrugTBL").append(
      //`drg_ID`, `drg_name`, `manf_comp`, `drg_strength`, `drg_Desc`, `drg_Img`
      "<thead><th>Drug ID</th><th>Name</th><th>Manufacture Company</th><th>Strength</th><th>Description</th><th>Image</th></thead><tbody id='adminViewMedicineTBody'>"
    );
    result.forEach(function (result) {
      $("#adminViewDrugTBL").append(
        "<tr><td>" +
          result.drg_ID +
          "</td><td>" +
          result.drg_name +
          "</td><td>" +
          result.manf_comp +
          "</td><td>" +
          result.drg_strength +
          "</td><td>" +
          result.drg_Desc +
          "</td><td>" +
          result.drg_Img +
          "</td></tr>"
      );
    });
    $("#adminViewDrugTBL").append("</tbody>");
  });
}

function getUserName() {
  return "Admin";
}



function downloadDoctorPDF() {
  document.getElementById('content').style.display = 'block';
  
  const invoice =this.document.getElementById("adminViewDoctoTBody");
  const internalTable = invoice.getInnerHTML();
  const d = new Date();
  const template="<img src='assets/images/icon/logo3.png' alt='logo'><h2 style='text-align: center'> Registerd Doctors </h2><br><table style='width: 100%;'><thead style='padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #04AA6D; color: white;'><tr><th>Doctor ID</th><th>Name</th><th>Phone No</th><th>Reg No</th><th>NIC</th><th>Email</th><tbody style='background-color: #f2f2f2;'>"+internalTable+"</tbody></table><p>"+d+"</p>";
  console.log(invoice)
  console.log(window)
  var opt = {
      margin: 1,
      filename: 'Doctors Report.pdf',
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
  };
  html2pdf().from(template).set(opt).save();
}

function downloadPharmacyPDF() {
  document.getElementById('content').style.display = 'block';
  
  const invoice =this.document.getElementById("adminViewPhamacyTBody");
  const internalTable = invoice.getInnerHTML();
  const d = new Date();
  const template="<img src='assets/images/icon/logo3.png' alt='logo'><h2 style='text-align: center'> Registerd Pharmacies </h2><br><table style='width: 100%; wrap:false'><thead style='padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #04AA6D; color: white;'><th>Phamacy ID</th><th>Name</th><th>Reg No</th><th>Email</th><th>Location</th><th>Phone</th><tbody style='background-color: #f2f2f2;'>"+internalTable+"</tbody></table><p>"+d+"</p>";
  console.log(invoice)
  console.log(window)
  var opt = {
      margin: 1,
      filename: 'Pharmacies Report.pdf',
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
  };
  html2pdf().from(template).set(opt).save();
}

function downloadMedicinePDF() {
  document.getElementById('content').style.display = 'block';
  
  const invoice =this.document.getElementById("adminViewMedicineTBody");
  const internalTable = invoice.getInnerHTML();
  const d = new Date();
  const template="<img src='assets/images/icon/logo3.png' alt='logo'><h2 style='text-align: center'> Registerd Medicines </h2><br><table style='width: 100%; wrap:false'><thead style='padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #04AA6D; color: white;'><th>Drug ID</th><th>Name</th><th>Manufacture Company</th><th>Strength</th><th>Description</th><th>Image</th><tbody style='background-color: #f2f2f2;'>"+internalTable+"</tbody></table><p>"+d+"</p>";
  console.log(invoice)
  console.log(window)
  var opt = {
      margin: 1,
      filename: 'Medicines Report.pdf',
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
  };
  html2pdf().from(template).set(opt).save();
}


function downloadPatientPDF() {
  document.getElementById('content').style.display = 'block';
  
  const invoice =this.document.getElementById("adminViewPatientTBody");
  const internalTable = invoice.getInnerHTML();
  const d = new Date();
  const template="<img src='assets/images/icon/logo3.png' alt='logo'><h2 style='text-align: center'> Registerd Patients </h2><br><table style='width: 100%; wrap:false'><thead style='padding-top: 12px; padding-bottom: 12px; text-align: left; background-color: #04AA6D; color: white;'><th>Patient ID</th><th>Name</th><th>Email</th><th>NIC</th><th>Age</th><th>Phone</th><th>Gender</th><tbody style='background-color: #f2f2f2;'>"+internalTable+"</tbody></table><p>"+d+"</p>";
  console.log(invoice)
  console.log(window)
  var opt = {
      margin: 1,
      filename: 'Patients Report.pdf',
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { scale: 2 },
      jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
  };
  html2pdf().from(template).set(opt).save();
}
