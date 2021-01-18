<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Model</title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <style>
  * {
    margin: 0;
    padding: 0;
  }

  model-viewer {
    background: -webkit-radial-gradient(circle, rgb(255, 255, 255), rgb(0, 0, 0));
  }

  .imgbox {
    display: grid;
    height: 100%;
  }

  .center-fit {
    width: 100%;
    height: 100vh;
    margin: auto;
  }

  /* This keeps child nodes hidden while the element loads */
  :not(:defined)>* {
    display: none;
  }

  .infoBoxMain {
    position: fixed;
    top: 0;
    right: 0;
    margin-top: 60px;
    margin-right: 60px;
    height: 100%;
    z-index: 99;
    transition: all 0.5s ease;
  }

  .ic_box {
    margin-top: 20px;
    transition: all 0.5s ease;
  }
  </style>
</head>

<body>
  <div class="imgbox">
    <a href="/" class="m-3 py-1 px-1 text-center bg-blue-400 border cursor-pointer rounded text-white">Back</a>
    <model-viewer id="model-viewer" class="center-fit" autoplay animation-name="Idle" src="storage/models/<?= $model ?>"
      auto-rotate camera-controls @if ($bg !=='none' ) skybox-image="storage/backgrounds/<?= $bg ?>" @endif>

      <div class="infoBoxMain">
        <div class="ic_box" id="a1" onclick="HideShowDiv(1)">
          <img src="{{asset('icons/crossSection.png')}}" alt="crossSection">
        </div>
        <div class="ic_box" id="a1" onclick="HideShowDiv(2)">
          <img src="{{asset('icons/annotation.png')}}" alt="annotation">
        </div>
        <div class="ic_box" id="a1" onclick="HideShowDiv(3)">
          <img src="{{asset('icons/download.png')}}" alt="download">
        </div>

        <div id="cross-section" class="infoShow" style="display:none">
          Hello from cross-section
        </div>
        <div id="annotation" class="infoShow" style="display:none">
          Hello from annotation
        </div>
        <div id="download" class="infoShow" style="display:none">
          Hello from download
        </div>
      </div>

    </model-viewer>

    <script>
    function HideShowDiv(id) {
      if (id == 1) {
        $("#cross-section").show();
        $("#download", "#annotation").hide();
      }
      if (id == 2) {
        $("#annotation").show();
        $("#cross-section", "#download").hide();
      }
      if (id == 3) {
        $("#download").show();
        $("#cross-section", "#annotation").hide();
      }
      // if ($('#div1').hasClass('active')) {
      //   $('#div1').animate({
      //     'right': '-201'
      //   });
      //   $('#div1, #a1').removeClass('active');
      //   $("#a1").parent().parent().removeClass('info_active');
      // } else {
      //   $('#div1').animate({
      //     'right': '0'
      //   });
      //   $('#div1, #a1').addClass('active');
      //   $('#div2, #div3, #div4, #div5, #a2, #a3, #a4').removeClass('active');
      //   $("#a1").parent().parent().addClass('info_active');
      // }
    }
    </script>
  </div>
</body>

</html>