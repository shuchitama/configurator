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
    OrbitControls
  } from 'https://unpkg.com/three@0.123.0/examples/jsm/controls/OrbitControls.js';
  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);

  // camera.lookAt(new THREE.Vector3(0, 0, 0));

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
  })

  const raycaster = new THREE.Raycaster();
  const mouse = new THREE.Vector2();

  const controls = new OrbitControls(camera, renderer.domElement);
  controls.target.set(0, 0.5, 0);
  controls.update();
  controls.enablePan = true;
  controls.enableDamping = true;

  let model = new THREE.Object3D();
  let box, center, boxSize;


  const loader = new GLTFLoader();
  // Load a glTF resource
  loader.load('storage/models/<?= $name ?>', function(gltf) {
      model = gltf.scene;

      // Get a bounding box for the model
      box = new THREE.Box3().setFromObject(model);
      center = box.getCenter(new THREE.Vector3());
      boxSize = box.getSize(new THREE.Vector3());

      // position model at origin
      model.position.x += (model.position.x - center.x);
      model.position.y += (model.position.y - center.y);
      model.position.z += (model.position.z - center.z);

      const maxDim = Math.max(boxSize.x, boxSize.z);
      console.log(boxSize.x, boxSize.y, boxSize.z);
      // console.log("boxSize - z:", boxSize.z);
      console.log("maxDim", maxDim);
      camera.position.z = (maxDim);

      scene.add(model);
    },
    undefined,
    function(e) {
      console.error(e);
    });


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

  const render = function() {
    requestAnimationFrame(render);
    renderer.render(scene, camera);
  }

  render();
  </script>
</body>

</html>