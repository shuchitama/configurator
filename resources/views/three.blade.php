<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Three</title>
</head>

<body>

  Three model here - <?= $name ?>
  <script type="module">
  import * as THREE from "https://unpkg.com/three@0.123.0/build/three.module.js"
  import {
    GLTFLoader
  } from "https://unpkg.com/three@0.123.0/examples/jsm/loaders/GLTFLoader.js"
  import {
    OrbitControls
  } from 'https://unpkg.com/three@0.123.0/examples/jsm/controls/OrbitControls.js';
  // import * as THREE from "../../node_modules/three/build/three.module.js"
  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
  camera.position.z = 5;
  camera.position.y = 5;
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

  const loader = new GLTFLoader();
  // Load a glTF resource
  loader.load('storage/models/<?= $name ?>', function(gltf) {

      scene.add(gltf.scene);

      gltf.animations; // Array<THREE.AnimationClip>
      gltf.scene; // THREE.Group
      gltf.scenes; // Array<THREE.Group>
      gltf.cameras; // Array<THREE.Camera>
      gltf.asset; // Object

    },
    undefined,
    function(e) {
      console.error(e);
    });


  const dirLight = new THREE.DirectionalLight(0xffffff, 1);
  dirLight.position.set(5, 2, 8);
  scene.add(dirLight);

  const render = function() {
    requestAnimationFrame(render);
    renderer.render(scene, camera);
  }

  render();
  </script>
</body>

</html>