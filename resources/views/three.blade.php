<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>Three</title>
</head>

<body>
  <div class="w-full grid">
    <a href="/" class="m-3 py-1 px-1 text-center bg-blue-400 border cursor-pointer rounded text-white">Back</a>
  </div>
  <script type="module">
  import * as THREE from "https://unpkg.com/three@0.123.0/build/three.module.js"
  import {
    GLTFLoader
  } from "https://unpkg.com/three@0.123.0/examples/jsm/loaders/GLTFLoader.js"
  import {
    RGBELoader
  } from "https://unpkg.com/three@0.123.0/examples/jsm/loaders/RGBELoader.js"
  import {
    OrbitControls
  } from 'https://unpkg.com/three@0.123.0/examples/jsm/controls/OrbitControls.js';
  const scene = new THREE.Scene();

  const fov = 75;
  const camera = new THREE.PerspectiveCamera(fov, window.innerWidth / window.innerHeight, 0.1, 5000);
  // camera.position.z = 10;
  camera.lookAt(new THREE.Vector3(0, 0, 0));

  const helperCamera = new THREE.PerspectiveCamera(fov, window.innerWidth / window.innerHeight, 0.1, 1000);
  helperCamera.lookAt(new THREE.Vector3(100, 100, 100));
  const cameraPerspectiveHelper = new THREE.CameraHelper(helperCamera);
  scene.add(cameraPerspectiveHelper);

  const axesHelper = new THREE.AxesHelper(50);
  scene.add(axesHelper);

  const renderer = new THREE.WebGLRenderer({
    antialias: true
  });
  renderer.setClearColor("#e5e5e5");
  renderer.setSize(window.innerWidth, window.innerHeight);

  document.body.appendChild(renderer.domElement);

  window.addEventListener('resize', () => {
    renderer.setSize(window.innerWidth, window.innerHeight);
    camera.aspect = window.innerWidth / window.innerHeight;

    camera.updateProjectionMatrix();
    console.log("camera position: ", camera.position.x, camera.position.y, camera.position.z)
  })

  const raycaster = new THREE.Raycaster();
  const mouse = new THREE.Vector2();

  const controls = new OrbitControls(camera, renderer.domElement);
  controls.target.set(0, 0, 0);
  controls.update();
  controls.enablePan = true;
  controls.enableDamping = true;

  let model = new THREE.Object3D();
  let box, center, boxSize;
  const pmremGenerator = new THREE.PMREMGenerator(renderer);
  pmremGenerator.compileEquirectangularShader(); // what does this do?

  new RGBELoader()
    .setDataType(THREE.UnsignedByteType)
    .setPath('storage/backgrounds/')
    .load('<?= $bg ?? 'spot1Lux.hdr' ?>', function(hdrEquirect) {

      const hdrCubeRenderTarget = pmremGenerator.fromEquirectangular(hdrEquirect);
      hdrEquirect.dispose();
      pmremGenerator.dispose();

      scene.background = hdrCubeRenderTarget.texture;
      scene.environment = hdrCubeRenderTarget.texture; // what does this do?
      loadGLTF();
    });


  const loadGLTF = function() {
    const loader = new GLTFLoader();
    // Load a glTF resource
    loader.load('storage/models/<?= $model ?>', function(gltf) {
        model = gltf.scene;

        // Get a bounding box for the model
        box = new THREE.Box3().setFromObject(model);
        center = box.getCenter(new THREE.Vector3());
        boxSize = box.getSize(new THREE.Vector3());

        // position model at origin
        model.position.x += (model.position.x - center.x);
        model.position.y += (model.position.y - center.y);
        model.position.z += (model.position.z - center.z);

        const maxDim = Math.max(boxSize.x, boxSize.y);
        // console.log("BOX SIZE: x:", boxSize.x, "y:", boxSize.y, "z:", boxSize.z);

        //convert fov to radians
        const fovRad = camera.fov * (Math.PI / 180);
        //calculate camera distance from center of object based on maxDim
        const cameraZ = (maxDim / 2) / (Math.tan(fovRad / 2));
        // console.log("Camera Z", cameraZ);
        // console.log("boxSize.z / 2", boxSize.z / 2);

        camera.position.z = cameraZ;
        // camera.position.z = cameraZ + boxSize.z / 2; <- to add some extra distance to camera 

        scene.add(model);
        // console.log("camera position: ",
        //   `(X: ${camera.position.x}, Y: ${camera.position.y}, Z: ${camera.position.z})`)
        // console.log("Helper camera position: ",
        //   `(X: ${helperCamera.position.x}, Y: ${helperCamera.position.y}, Z: ${helperCamera.position.z})`)

      },
      undefined,
      function(e) {
        console.error(e);
      });
  }

  let dirLight = new THREE.DirectionalLight(0xffffff, 1);
  dirLight.position.set(0, 1, 0);
  scene.add(dirLight);

  dirLight = new THREE.DirectionalLight(0xffffff, 1);
  dirLight.position.set(1, 0, 0);
  scene.add(dirLight);

  dirLight = new THREE.DirectionalLight(0xffffff, 1);
  dirLight.position.set(0, 0, 1);
  scene.add(dirLight);

  dirLight = new THREE.DirectionalLight(0xffffff, 1);
  dirLight.position.set(0, 0, -1);
  scene.add(dirLight);

  dirLight = new THREE.DirectionalLight(0xffffff, 1);
  dirLight.position.set(-1, -1, 0);
  scene.add(dirLight);

  let delta = 0;
  const render = function() {
    // delta += 0.01;
    // camera.position.x = Math.sin(delta) * 20;
    // camera.position.y = Math.sin(delta) * 20;
    requestAnimationFrame(render);
    renderer.render(scene, camera);
  }

  render();
  </script>
</body>

</html>