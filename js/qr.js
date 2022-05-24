function downloadPDF() {
  const qrcodeItem = this.document.getElementById("qrcode");
  console.log(window);
  var opt = {
    margin: 1,
    filename: "queuecode.pdf",
    image: { type: "jpeg", quality: 0.98 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: "in", format: "letter", orientation: "portrait" },
  };
  html2pdf().from(qrcodeItem).set(opt).save();
}
function generateQR(key) {
  const qrcodeItem = this.document.getElementById("qrcode");
  var qrcode = new QRCode(qrcodeItem);
  qrcode.makeCode(key);
}
