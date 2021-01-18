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
    height: 100%;
    z-index: 99;
    transition: all 0.5s ease;
  }

  .infoBoxMain.info_active {
    width: 250px;
    transition: all 0.5s ease;
  }

  .iconsBox {
    margin-top: 60px;
  }

  .ic_box {
    display: block;
    width: 42px;
    background: transparent;
    text-align: center;
    border-radius: 50%;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    cursor: pointer;
    margin: 0 0 32px -110px;
    position: relative;
    transition: all 0.5s ease;
  }

  .infoShow {
    background: rgba(255, 255, 255, 0.7);
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
    padding: 60px 22px;
    width: 100%;
    overflow-x: hidden;
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
        <div class="iconsBox">
          <div class="ic_box" id="a1" onclick="HideShowDiv(1)">
            <img src="{{asset('icons/crossSection.png')}}" alt="crossSection">
          </div>
          <div class="ic_box" id="a1" onclick="HideShowDiv(2)">
            <img src="{{asset('icons/annotation.png')}}" alt="annotation">
          </div>
          <div class="ic_box" id="a1" onclick="HideShowDiv(3)">
            <img src="{{asset('icons/download.png')}}" alt="download">
          </div>
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
        $("#download, #annotation").hide();
        if ($('#cross-section').hasClass('active')) {
          $('#cross-section').animate({
            'right': '-201'
          });
          $('#cross-section, #a1').removeClass('active');
          $(".infoBoxMain").removeClass('info_active');
        } else {
          $('#cross-section').animate({
            'right': '0'
          });
          $('#cross-section').addClass('active');
          $('#annotation, #download').removeClass('active');
          $(".infoBoxMain").addClass('info_active');
        }
      }
      if (id == 2) {
        $("#annotation").show();
        $("#cross-section, #download").hide();
        if ($('#annotation').hasClass('active')) {
          $('#annotation').animate({
            'right': '-201'
          });
          $('#annotation, #a1').removeClass('active');
          $(".infoBoxMain").removeClass('info_active');
        } else {
          $('#annotation').animate({
            'right': '0'
          });
          $('#annotation').addClass('active');
          $('#cross-section, #download').removeClass('active');
          $(".infoBoxMain").addClass('info_active');
        }
      }
      if (id == 3) {
        $("#download").show();
        $("#cross-section, #annotation").hide();
        if ($('#download').hasClass('active')) {
          $('#download').animate({
            'right': '-201'
          });
          $('#download, #a1').removeClass('active');
          $(".infoBoxMain").removeClass('info_active');
        } else {
          $('#download').animate({
            'right': '0'
          });
          $('#download').addClass('active');
          $('#annotation, #cross-section').removeClass('active');
          $(".infoBoxMain").addClass('info_active');
        }
      }
    }
    </script>
  </div>
</body>

</html>