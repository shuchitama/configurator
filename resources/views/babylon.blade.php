<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <title>BabylonJs Demo</title>
    <script src="https://cdn.babylonjs.com/babylon.js"></script>
    <script src="https://cdn.babylonjs.com/loaders/babylonjs.loaders.min.js"></script>
    <style>
        #canvas {
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="w-full grid">
      <a href="/" class="m-3 py-1 px-1 text-center bg-blue-400 border cursor-pointer rounded text-white">Back</a>
    </div>
    <canvas id="canvas"></canvas>
    <script>
        const canvas = document.getElementById("canvas");
        const engine = new BABYLON.Engine(canvas, true);

        const createScene = function() {
            const scene = new BABYLON.Scene(engine);
            scene.clearColor = new BABYLON.Color3.FromHexString("#e5e5e5");

            const camera = new BABYLON.ArcRotateCamera("arcCam", //name
            BABYLON.Tools.ToRadians(90), // alpha - the longitudinal rotation, in radians
            BABYLON.Tools.ToRadians(90), // beta - latitudinal rotation, in radians
            290.0, // radius
            BABYLON.Vector3.Zero(), // target position
            scene // scene
            );
            // camera.setTarget(BABYLON.Vector3.Zero());
            camera.attachControl(canvas, true); //mouse control

            // scene.createDefaultCamera(true, true, true);
            // scene.createDefaultEnvironment();

            const light = new BABYLON.HemisphericLight("HemiLight", new BABYLON.Vector3(0, 1, 0), scene);

            BABYLON.SceneLoader.ImportMesh("", "storage/models/", "<?=$model?>", scene, function(newMeshes) {
              scene.executeWhenReady(function () {
                console.log("newMeshes", newMeshes)
                // camera.setTarget(newMeshes[0].position);
                newMeshes[0].showBoundingBox = true;
                newMeshes[1].showBoundingBox = true;
                console.log("newMeshes[1]", newMeshes[1])
                let boundingInfo = newMeshes[1].getBoundingInfo()
                console.log("boundinginfo: ", boundingInfo);
                camera.setTarget(boundingInfo.boundingBox.center);
              })
            });

            return scene;
        }

        const scene = createScene();
        engine.runRenderLoop(function() {
            scene.render();
        });
        window.addEventListener("resize", function () {
            engine.resize();
        });
    </script>
</body>
</html>