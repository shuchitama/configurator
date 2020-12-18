<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Model</title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
  <style>
  * {
    margin: 0;
    padding: 0;
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

  button {
    display: block;
    width: 20px;
    height: 20px;
    border-radius: 10px;
    border: none;
    background-color: blue;
    box-sizing: border-box;
  }

  button[slot="hotspot-hand"] {
    --min-hotspot-opacity: 0;
    background-color: red;
  }

  button[slot="hotspot-foot"]:not([data-visible]) {
    background-color: transparent;
    border: 3px solid blue;
  }

  #annotation {
    background-color: #888888;
    position: absolute;
    transform: translate(10px, 10px);
    border-radius: 10px;
    padding: 10px;
  }

  /* This keeps child nodes hidden while the element loads */
  :not(:defined)>* {
    display: none;
  }
  </style>
</head>

<body>
  <div class="imgbox">
    <a href="/" class="m-3 py-1 px-1 text-center bg-blue-400 border cursor-pointer rounded text-white">Back</a>
    <model-viewer onload="myFunction()" id="model-viewer" autoplay animation-name="Death"
      skybox-image="storage/backgrounds/<?= $bg ?? 'quarry_01_1k.hdr' ?>" class="center-fit"
      src="storage/models/<?= $model ?>" auto-rotate camera-controls>
    </model-viewer>
    <script>
    function myFunction() {
      const modelViewer = document.querySelector('#model-viewer');
      modelViewer.currentTime -= 0.03;
      requestAnimationFrame(myFunction);
    }
    </script>
  </div>
</body>

</html>