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
    <model-viewer id="model-viewer" autoplay animation-name="Running"
      skybox-image="storage/backgrounds/<?= $bg ?? 'quarry_01_1k.hdr' ?>" class="center-fit"
      src="storage/models/<?= $model ?>" auto-rotate camera-controls>
      <!-- <button slot="hotspot-visor" data-position="0 1.75 0.35" data-normal="0 0 1"></button>
      <button slot="hotspot-hand" data-position="-0.54 0.93 0.1" data-normal="-0.73 0.05 0.69">
        <div id="annotation">This hotspot disappears completely</div>
      </button>
      <button slot="hotspot-foot" data-position="0.16 0.1 0.17" data-normal="-0.07 0.97 0.23"
        data-visibility-attribute="visible"></button>
      <div id="annotation">This annotation is fixed in screen-space</div> -->
    </model-viewer>
    <script>
    (() => {
      const modelViewer = document.querySelector('#model-viewer');

      self.setInterval(() => {
        modelViewer.animationName = modelViewer.animationName === 'Running' ?
          'Wave' : 'Running';
      }, 1500.0);
    })();
    </script>
  </div>
</body>

</html>