<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <canvas id="canvas"></canvas>
    <script>
        const canvas = document.getElementById("canvas");
        const engine = new BABYLON.Engine(canvas, true);

        const createScene = function() {
            const scene = new BABYLON.Scene(engine);
            scene.clearColor = new BABYLON.Color3.White();
            
            const camera = new BABYLON.ArcRotateCamera("arcCam", //name
            BABYLON.Tools.ToRadians(45), // alpha - the longitudinal rotation, in radians
            BABYLON.Tools.ToRadians(45), // beta - latitudinal rotation, in radians
            10.0, // radius
            BABYLON.Vector3.Zero(), // target position
            scene // scene
            );
            camera.setTarget(BABYLON.Vector3.Zero());
            camera.attachControl(canvas, true); //mouse control

            // scene.createDefaultCameraOrLight(true, true, true);
            // scene.createDefaultEnvironment();

            const light = new BABYLON.HemisphericLight("HemiLight", new BABYLON.Vector3(0, 1, 0), scene);

            BABYLON.SceneLoader.ImportMeshAsync("", "storage/models/", "<?= $model ?>");

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