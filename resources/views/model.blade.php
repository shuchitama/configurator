<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Model</title>
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
  </style>
</head>

<body>
  <div class="imgbox">
    <model-viewer class="center-fit" src="storage/models/RobotExpressive.glb" auto-rotate camera-controls>
    </model-viewer>
  </div>
</body>

</html>