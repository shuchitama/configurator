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
  import {
    DecalGeometry
  } from 'https://threejs.org/examples/jsm/geometries/DecalGeometry.js';
  import {
    GUI
  } from 'https://threejs.org/examples/jsm/libs/dat.gui.module.js';

  let container = document.createElement('div');
  document.body.appendChild(container);

  let renderer, scene, camera;
  let mesh;
  let planes, planeObjects, planeHelpers, object;

  let textureLoader = new THREE.TextureLoader();

  let params = {
    planeX: {
      constant: 0,
      negated: false,
      displayHelper: true
    },
    planeY: {
      constant: 0,
      negated: false,
      displayHelper: true
    },
    planeZ: {
      constant: 0,
      negated: false,
      displayHelper: true
    }
  };

  window.addEventListener('load', init);

  function init() {
    renderer = new THREE.WebGLRenderer({
      antialias: true
    });
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setClearColor("#e5e5e5");
    renderer.localClippingEnabled = true;
    container.appendChild(renderer.domElement);

    scene = new THREE.Scene();

    camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.001, 5000);
    camera.position.z = 100;
    camera.lookAt(new THREE.Vector3(0, 0, 0));

    const axesHelper = new THREE.AxesHelper(50);
    scene.add(axesHelper);

    let controls = new OrbitControls(camera, renderer.domElement);
    controls.update();
    controls.enablePan = true;
    controls.enableDamping = true;

    //lights

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

    // NOTE: Setup Plane
    planes = [
      new THREE.Plane(new THREE.Vector3(-1, 0, 0), 50),
      new THREE.Plane(new THREE.Vector3(0, -1, 0), 12),
      new THREE.Plane(new THREE.Vector3(0, 0, -1), 50)
    ];

    planeHelpers = planes.map(p => new THREE.PlaneHelper(p, 50, 0xffffff));
    planeHelpers.forEach(ph => {

      ph.visible = true;
      scene.add(ph);

    });


    object = new THREE.Group();
    object.scale.set(10, 10, 10);
    scene.add(object);

    loadModel();

    window.addEventListener('resize', onWindowResize, false);

    let gui = new GUI();
    let planeX = gui.addFolder('planeX');
    planeX.add(params.planeX, 'displayHelper').onChange(v => planeHelpers[0].visible = v);
    planeX.add(params.planeX, 'constant').min(-50).max(50).onChange(d => planes[0].constant = d);
    planeX.add(params.planeX, 'negated').onChange(() => {

      planes[0].negate();
      params.planeX.constant = planes[0].constant;

    });
    planeX.open(); // GUI folder default state is open

    let planeY = gui.addFolder('planeY');
    planeY.add(params.planeY, 'displayHelper').onChange(v => planeHelpers[1].visible = v);
    planeY.add(params.planeY, 'constant').min(-50).max(50).onChange(d => planes[1].constant = d);
    planeY.add(params.planeY, 'negated').onChange(() => {

      planes[1].negate();
      params.planeY.constant = planes[1].constant;

    });
    planeY.open();

    let planeZ = gui.addFolder('planeZ');
    planeZ.add(params.planeZ, 'displayHelper').onChange(v => planeHelpers[2].visible = v);
    planeZ.add(params.planeZ, 'constant').min(-50).max(50).onChange(d => planes[2].constant = d);
    planeZ.add(params.planeZ, 'negated').onChange(() => {

      planes[2].negate();
      params.planeZ.constant = planes[2].constant;

    });
    planeZ.open();

    gui.open();

    onWindowResize();
    animate();

  } // end init fn

  function createPlaneStencilGroup(geometry, plane, renderOrder) {

    let group = new THREE.Group();
    let baseMat = new THREE.MeshBasicMaterial();
    baseMat.depthWrite = false;
    baseMat.depthTest = false;
    baseMat.colorWrite = false;
    baseMat.stencilWrite = true;
    baseMat.stencilFunc = THREE.AlwaysStencilFunc;

    // back faces
    let mat0 = baseMat.clone();
    mat0.side = THREE.BackSide;
    mat0.clippingPlanes = [plane];
    mat0.stencilFail = THREE.IncrementWrapStencilOp;
    mat0.stencilZFail = THREE.IncrementWrapStencilOp;
    mat0.stencilZPass = THREE.IncrementWrapStencilOp;

    let mesh0 = new THREE.Mesh(geometry, mat0);
    mesh0.renderOrder = renderOrder;
    group.add(mesh0);

    // front faces
    let mat1 = baseMat.clone();
    mat1.side = THREE.FrontSide;
    mat1.clippingPlanes = [plane];
    mat1.stencilFail = THREE.DecrementWrapStencilOp;
    mat1.stencilZFail = THREE.DecrementWrapStencilOp;
    mat1.stencilZPass = THREE.DecrementWrapStencilOp;

    let mesh1 = new THREE.Mesh(geometry, mat1);
    mesh1.renderOrder = renderOrder;

    group.add(mesh1);

    return group;

  } // end createPlaneStencilGroup

  function loadModel() {
    let loader = new GLTFLoader();

    loader.load('storage/models/GLTF2/Koi.gltf', function(gltf) {

      mesh = gltf.scene.children[0];
      console.log("mesh", mesh);
      console.log("scene", gltf.scene);

      // Set up clip plane rendering
      planeObjects = [];
      let planeGeom = new THREE.PlaneBufferGeometry(100, 100); // width and height
      for (let i = 0; i < 3; i++) {

        let poGroup = new THREE.Group();
        let plane = planes[i];
        let stencilGroup = createPlaneStencilGroup(mesh.geometry, plane, i + 1);

        // plane is clipped by the other clipping planes
        let planeMat =
          new THREE.MeshStandardMaterial({

            color: 0xE91E63, //magenta
            metalness: 0.1,
            roughness: 0.75,
            clippingPlanes: planes.filter(p => p !== plane),
            stencilWrite: true,
            stencilRef: 0,
            stencilFunc: THREE.NotEqualStencilFunc,
            stencilFail: THREE.ReplaceStencilOp,
            stencilZFail: THREE.ReplaceStencilOp,
            stencilZPass: THREE.ReplaceStencilOp,
          });
        let po = new THREE.Mesh(planeGeom, planeMat);
        po.onAfterRender = function(renderer) {

          renderer.clearStencil();

        };

        po.renderOrder = i + 1.1;
        object.add(stencilGroup);
        poGroup.add(po);
        planeObjects.push(po);
        scene.add(poGroup);
        scene.add(mesh);
      }

      let material = new THREE.MeshStandardMaterial({

        color: 0xFFC107, //yellow
        metalness: 0.1,
        roughness: 0.75,
        clippingPlanes: planes,
        clipShadows: true,
        shadowSide: THREE.DoubleSide,
      });

      // add the color
      let clippedColorFront = new THREE.Mesh(mesh.geometry, material);
      // clippedColorFront.castShadow =
      //   true;
      clippedColorFront.renderOrder = 6;
      object.add(clippedColorFront);

      mesh.scale.set(10, 10, 10);
    });

  }

  function onWindowResize() {

    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();

    renderer.setSize(window.innerWidth, window.innerHeight);

  }

  function animate() {
    if (planeObjects && planeObjects.length > 0) {
      for (let i = 0; i < planeObjects.length; i++) {

        let plane = planes[i];
        let po = planeObjects[i];
        plane.coplanarPoint(po.position);
        po.lookAt(
          po.position.x - plane.normal.x,
          po.position.y - plane.normal.y,
          po.position.z - plane.normal.z,
        );

      }
    }

    requestAnimationFrame(animate);

    renderer.render(scene, camera);

  }
  </script>
</body>

</html>