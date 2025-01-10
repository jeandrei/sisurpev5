<?php require APPROOT . '/views/inc/header.php'; ?>
<style>

.error {
  width: 100%;
  background-color: red;
  padding: 10px;
  font-weight: bold;
  color: white;
}

.success {
  width: 100%;
  background-color: green;
  padding: 10px;
  font-weight: bold;
  color: white;
}

.container {
    width: 100%;
    max-width: 500px;
    margin: 5px;
}

.container h1 {
    color: #ffffff;
}

.section {
    background-color: #ffffff;
    padding: 50px 30px;
    border: 1.5px solid #b2b2b2;
    border-radius: 0.25em;
    box-shadow: 0 20px 25px rgba(0, 0, 0, 0.25);
}

#my-qr-reader {
    padding: 20px !important;
    border: 1.5px solid #b2b2b2 !important;
    border-radius: 8px;
}

#my-qr-reader img[alt="Info icon"] {
    display: none;
}

#my-qr-reader img[alt="Camera based scan"] {
    width: 100px !important;
    height: 100px !important;
}

button {
    padding: 10px 20px;
    border: 1px solid #b2b2b2;
    outline: none;
    border-radius: 0.25em;
    color: white;
    font-size: 15px;
    cursor: pointer;
    margin-top: 15px;
    margin-bottom: 10px;
    background-color: #008000ad;
    transition: 0.3s background-color;
}

button:hover {
    background-color: #008000;
}

#html5-qrcode-anchor-scan-type-change {
    text-decoration: none !important;
    color: #1d9bf0;
}

video {
    width: 100% !important;
    border: 1px solid #b2b2b2 !important;
    border-radius: 0.25em;
}

</style>

<div class="row align-items-center mb-3">
  <div class="col-md-12">
    <p><?php echo $data['description'];?></p>
  </div>
</div>

<div id="messageBox"></div>

<!-- leitor de qr code depende da biblioteca public/js/html5-qrcode.min.js -->
<div class="container">  
  <div class="section">
      <div id="my-qr-reader">
      </div>
  </div>
</div>  

<script>

  function domReady(fn) {
    if (
        document.readyState === "complete" ||
        document.readyState === "interactive"
    ) {
        setTimeout(fn, 2000);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
  }

  domReady(function () {
    
    function onScanSuccess(decodeText, decodeResult) {       
      ajaxGet(decodeText);
    }

    let htmlscanner = new Html5QrcodeScanner(
        "my-qr-reader",
        { fps: 1, qrbos: 250 }
    );
    htmlscanner.render(onScanSuccess);
  });
  
  function ajaxGet(url){ 
    //Para poder pegar os valores passados pela url ex ?userId=1 urlParams.get('userId')
    const urlParams = new URLSearchParams(url);    
    $.ajax({
      url: url,
      method:'POST',
      data:{
        abre_presenca_id:urlParams.get('presenca_em_andamento'),
        user_id:urlParams.get('userId')
      },
    success: function(retorno_php){        
      let responseObj = JSON.parse(retorno_php);       
      $("#messageBox")
      .removeClass()      
      .addClass(responseObj.class)                           
      .html(responseObj.message) 
      .fadeIn(1000).fadeOut(6000);       
      }
    });  
  }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>