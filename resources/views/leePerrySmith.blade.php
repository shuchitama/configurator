<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <title>LeePerrySmith</title>
</head>

<body>
  <div class="w-full grid">
    <a href="/" class="m-3 py-1 px-1 text-center bg-blue-400 border cursor-pointer rounded text-white">Back</a>
  </div>
  <script type="module">
  import * as THREE from "https://threejs.org/build/three.module.js";

  import Stats from "https://threejs.org/examples/jsm/libs/stats.module.js";
  import {
    GUI
  } from "https://threejs.org/examples/jsm/libs/dat.gui.module.js";

  import {
    OrbitControls
  } from "https://threejs.org/examples/jsm/controls/OrbitControls.js";
  import {
    GLTFLoader
  } from "https://threejs.org/examples/jsm/loaders/GLTFLoader.js";
  import {
    DecalGeometry
  } from "https://threejs.org/examples/jsm/geometries/DecalGeometry.js";

  let container = document.createElement('div');
  document.body.appendChild(container);

  var renderer, scene, camera, stats;
  var mesh;
  var planes, planeObjects, planeHelpers, object;

  var textureLoader = new THREE.TextureLoader();
  var size = new THREE.Vector3(10, 10, 10);

  var params = {
    planeX: {
      constant: 0,
      negated: false,
      displayHelper: false
    },
    planeY: {
      constant: 0,
      negated: false,
      displayHelper: false
    },
    planeZ: {
      constant: 0,
      negated: false,
      displayHelper: false
    }
  };

  window.addEventListener("load", init);

  function init() {
    renderer = new THREE.WebGLRenderer({
      antialias: true
    });
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.localClippingEnabled = true;
    container.appendChild(renderer.domElement);

    stats = new Stats();
    container.appendChild(stats.dom);

    scene = new THREE.Scene();

    camera = new THREE.PerspectiveCamera(
      45,
      window.innerWidth / window.innerHeight,
      1,
      1000
    );
    camera.position.z = 100;
    camera.position.y = 40;
    camera.position.x = 60;
    camera.target = new THREE.Vector3();

    var controls = new OrbitControls(camera, renderer.domElement);
    controls.minDistance = 50;
    controls.maxDistance = 200;

    scene.add(new THREE.AmbientLight(0x443333));

    var light = new THREE.DirectionalLight(0xffddcc, 1);
    light.position.set(1, 0.75, 0.5);
    scene.add(light);

    var light = new THREE.DirectionalLight(0xccccff, 1);
    light.position.set(-1, 0.75, -0.5);
    scene.add(light);

    // NOTE: Setup Plane
    planes = [
      new THREE.Plane(new THREE.Vector3(-1, 0, 0), 50),
      new THREE.Plane(new THREE.Vector3(0, -1, 0), 12),
      new THREE.Plane(new THREE.Vector3(0, 0, -1), 50)
    ];

    planeHelpers = planes.map(p => new THREE.PlaneHelper(p, 50, 0xffffff));
    planeHelpers.forEach(ph => {
      ph.visible = false;
      scene.add(ph);
    });

    object = new THREE.Group();
    object.scale.set(10, 10, 10);
    scene.add(object);

    loadLeePerrySmith();

    window.addEventListener("resize", onWindowResize, false);

    var moved = false;

    controls.addEventListener("change", function() {
      moved = true;
    });

    var gui = new GUI();
    var planeX = gui.addFolder("planeX");
    planeX
      .add(params.planeX, "displayHelper")
      .onChange(v => (planeHelpers[0].visible = v));
    planeX
      .add(params.planeX, "constant")
      .min(-50)
      .max(50)
      .onChange(d => (planes[0].constant = d));
    planeX.add(params.planeX, "negated").onChange(() => {
      planes[0].negate();
      params.planeX.constant = planes[0].constant;
    });
    planeX.open();

    var planeY = gui.addFolder("planeY");
    planeY
      .add(params.planeY, "displayHelper")
      .onChange(v => (planeHelpers[1].visible = v));
    planeY
      .add(params.planeY, "constant")
      .min(-50)
      .max(50)
      .onChange(d => (planes[1].constant = d));
    planeY.add(params.planeY, "negated").onChange(() => {
      planes[1].negate();
      params.planeY.constant = planes[1].constant;
    });
    planeY.open();

    var planeZ = gui.addFolder("planeZ");
    planeZ
      .add(params.planeZ, "displayHelper")
      .onChange(v => (planeHelpers[2].visible = v));
    planeZ
      .add(params.planeZ, "constant")
      .min(-50)
      .max(50)
      .onChange(d => (planes[2].constant = d));
    planeZ.add(params.planeZ, "negated").onChange(() => {
      planes[2].negate();
      params.planeZ.constant = planes[2].constant;
    });
    planeZ.open();

    gui.open();

    onWindowResize();
    animate();
  }

  function createPlaneStencilGroup(geometry, plane, renderOrder) {
    var group = new THREE.Group();
    var baseMat = new THREE.MeshBasicMaterial();
    baseMat.depthWrite = false;
    baseMat.depthTest = false;
    baseMat.colorWrite = false;
    baseMat.stencilWrite = true;
    baseMat.stencilFunc = THREE.AlwaysStencilFunc;

    // back faces
    var mat0 = baseMat.clone();
    mat0.side = THREE.BackSide;
    mat0.clippingPlanes = [plane];
    mat0.stencilFail = THREE.IncrementWrapStencilOp;
    mat0.stencilZFail = THREE.IncrementWrapStencilOp;
    mat0.stencilZPass = THREE.IncrementWrapStencilOp;

    var mesh0 = new THREE.Mesh(geometry, mat0);
    mesh0.renderOrder = renderOrder;
    group.add(mesh0);

    // front faces
    var mat1 = baseMat.clone();
    mat1.side = THREE.FrontSide;
    mat1.clippingPlanes = [plane];
    mat1.stencilFail = THREE.DecrementWrapStencilOp;
    mat1.stencilZFail = THREE.DecrementWrapStencilOp;
    mat1.stencilZPass = THREE.DecrementWrapStencilOp;

    var mesh1 = new THREE.Mesh(geometry, mat1);
    mesh1.renderOrder = renderOrder;

    group.add(mesh1);

    return group;
  }

  function loadLeePerrySmith() {
    var loader = new GLTFLoader();

    loader.load(
      "https://threejs.org/examples/models/gltf/LeePerrySmith/LeePerrySmith.glb",
      function(gltf) {
        mesh = gltf.scene.children[0];

        mesh.material = new THREE.MeshPhongMaterial({
          specular: 0x111111,
          map: textureLoader.load(
            "https://threejs.org/examples/models/gltf/LeePerrySmith/Map-COL.jpg"
          ),
          specularMap: textureLoader.load(
            "https://threejs.org/examples/models/gltf/LeePerrySmith/Map-SPEC.jpg"
          ),
          normalMap: textureLoader.load(
            "https://threejs.org/examples/models/gltf/LeePerrySmith/Infinite-Level_02_Tangent_SmoothUV.jpg"
          ),
          shininess: 25
        });

        // Set up clip plane rendering
        planeObjects = [];
        var planeGeom = new THREE.PlaneBufferGeometry(100, 100);
        for (var i = 0; i < 3; i++) {
          var poGroup = new THREE.Group();
          var plane = planes[i];
          var stencilGroup = createPlaneStencilGroup(
            mesh.geometry,
            plane,
            i + 1
          );

          // plane is clipped by the other clipping planes
          var planeMat = new THREE.MeshStandardMaterial({
            color: 0xe91e63,
            metalness: 0.1,
            roughness: 0.75,
            clippingPlanes: planes.filter(p => p !== plane),

            stencilWrite: true,
            stencilRef: 0,
            stencilFunc: THREE.NotEqualStencilFunc,
            stencilFail: THREE.ReplaceStencilOp,
            stencilZFail: THREE.ReplaceStencilOp,
            stencilZPass: THREE.ReplaceStencilOp
          });
          var po = new THREE.Mesh(planeGeom, planeMat);
          po.onAfterRender = function(renderer) {
            renderer.clearStencil();
          };

          po.renderOrder = i + 1.1;
          object.add(stencilGroup);
          poGroup.add(po);
          planeObjects.push(po);
          scene.add(poGroup);
        }

        var material = new THREE.MeshStandardMaterial({
          // color: 0xFFC107,
          specular: 0x111111,
          map: textureLoader.load(
            "https://threejs.org/examples/models/gltf/LeePerrySmith/Map-COL.jpg"
          ),
          specularMap: textureLoader.load(
            "https://threejs.org/examples/models/gltf/LeePerrySmith/Map-SPEC.jpg"
          ),
          normalMap: textureLoader.load(
            "https://threejs.org/examples/models/gltf/LeePerrySmith/Infinite-Level_02_Tangent_SmoothUV.jpg"
          ),
          shininess: 25,
          metalness: 0.1,
          roughness: 0.75,
          clippingPlanes: planes,
          clipShadows: true,
          shadowSide: THREE.DoubleSide
        });

        // add the color
        var clippedColorFront = new THREE.Mesh(mesh.geometry, material);
        clippedColorFront.castShadow = true;
        clippedColorFront.renderOrder = 6;
        object.add(clippedColorFront);

        // scene.add(mesh);
        mesh.scale.set(10, 10, 10);
      }
    );
  }

  function onWindowResize() {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();

    renderer.setSize(window.innerWidth, window.innerHeight);
  }

  function animate() {
    if (planeObjects && planeObjects.length > 0) {
      for (var i = 0; i < planeObjects.length; i++) {
        var plane = planes[i];
        var po = planeObjects[i];
        plane.coplanarPoint(po.position);
        po.lookAt(
          po.position.x - plane.normal.x,
          po.position.y - plane.normal.y,
          po.position.z - plane.normal.z
        );
      }
    }

    requestAnimationFrame(animate);

    renderer.render(scene, camera);

    stats.update();
  }
  </script>
</body>

</html>