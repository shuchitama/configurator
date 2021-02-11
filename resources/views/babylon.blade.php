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
            20.0, // radius
            BABYLON.Vector3.Zero(), // target position
            scene // scene
            );

            // camera.setTarget(BABYLON.Vector3.Zero());
            camera.attachControl(canvas, true); //mouse control
            camera.lowerRadiusLimit = 1;

            // scene.createDefaultCamera(true, true, true);
            // scene.createDefaultEnvironment();

            const light = new BABYLON.HemisphericLight("HemiLight", new BABYLON.Vector3(0, 1, 0), scene);

            BABYLON.SceneLoader.ImportMesh("", "storage/models/", "<?=$model?>", scene, function(meshes) {
              scene.executeWhenReady(function () {
                console.log("meshes", meshes)

                // create a parent mesh
                let parent = new BABYLON.Mesh("parent", scene);

                // set as parent of all meshes
                for(let i=0; i<meshes.length; i++) {
                  meshes[i].setParent(parent);
                }

                let childMeshes = parent.getChildMeshes();
                console.log("childMeshes :", childMeshes)

                // calculate bounding box based on all meshes
                let min = childMeshes[0].getBoundingInfo().boundingBox.minimumWorld;
                let max = childMeshes[0].getBoundingInfo().boundingBox.maximumWorld;

                for(let i=0; i<childMeshes.length; i++){
                  let meshMin = childMeshes[i].getBoundingInfo().boundingBox.minimumWorld;
                  let meshMax = childMeshes[i].getBoundingInfo().boundingBox.maximumWorld;

                  min = BABYLON.Vector3.Minimize(min, meshMin);
                  max = BABYLON.Vector3.Maximize(max, meshMax);
                }

                parent.setBoundingInfo(new BABYLON.BoundingInfo(min, max));

                parent.showBoundingBox = true;

                // meshes[0].showBoundingBox = true;
                // meshes[1].showBoundingBox = true;
                // console.log("meshes[1]", meshes[1])
                // let boundingInfo = meshes[1].getBoundingInfo()
                // console.log("boundinginfo: ", parent._boundingInfo.boundingBox);
                camera.setTarget(parent._boundingInfo.boundingBox.center);
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