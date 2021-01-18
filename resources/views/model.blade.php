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
    /* display: block; */
    border: solid 1px;
    padding: 0 5px;
    /* background-color:  */
    /* box-sizing: border-box; */
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
    <model-viewer id="model-viewer" class="center-fit" autoplay animation-name="Death"
      src="storage/models/<?= $model ?>" camera-controls @if ($bg !=='none' )
      skybox-image="storage/backgrounds/<?= $bg ?>" @endif>
      <button id="reverse" onclick="reverse()">Reverse Animation</button>
      <button id="reset" onclick="reset()">Reset Animation</button>
    </model-viewer>

    <script>
    let requestID;

    function reverse() {
      const modelViewer = document.querySelector('#model-viewer');
      modelViewer.currentTime -= 0.03;
      requestID = requestAnimationFrame(reverse);
    }

    function reset() {
      const modelViewer = document.querySelector('#model-viewer');
      cancelAnimationFrame(requestID);
      modelViewer.currentTime = 0;
      modelViewer.play();
    }
    </script>
  </div>
</body>

</html>