<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Model</title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  <script type="module" src="https://unpkg.com/@google/model-viewer/dist/model-viewer.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <style>
  * {
    margin: 0;
    padding: 0;
  }

  model-viewer {
    background: -webkit-radial-gradient(circle, rgb(255, 255, 255), rgb(0, 0, 0));
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

  /* This keeps child nodes hidden while the element loads */
  :not(:defined)>* {
    display: none;
  }

  .infoBoxMain {
    position: fixed;
    top: 0;
    right: 0;
    height: 100%;
    z-index: 99;
    transition: all 0.5s ease;
  }

  .infoBoxMain.info_active {
    width: 250px;
    transition: all 0.5s ease;
  }

  .iconsBox {
    margin-top: 60px;
  }

  .ic_box {
    display: block;
    width: 42px;
    background: transparent;
    text-align: center;
    border-radius: 50%;
    -moz-border-radius: 50%;
    -webkit-border-radius: 50%;
    cursor: pointer;
    margin: 0 0 32px -110px;
    position: relative;
    transition: all 0.5s ease;
  }

  .infoShow {
    background: rgba(255, 255, 255, 0.7);
    position: absolute;
    top: 0;
    right: 0;
    height: 100%;
    padding: 60px 22px;
    width: 100%;
    overflow-x: hidden;
    transition: all 0.5s ease;
  }

  .infoShow h2 {
    letter-spacing: 0;
    font-size: 20px;
    line-height: normal;
    font-family: 'Roboto Condensed', sans-serif;
    color: #000000;
    font-weight: 700;
    text-align: center;
  }
  </style>
</head>

<body>
  <div class="imgbox">
    <a href="/" class="m-3 py-1 px-1 text-center bg-blue-400 border cursor-pointer rounded text-white">Back</a>
    <model-viewer id="model-viewer" class="center-fit" src="storage/models/<?= $model ?>" camera-controls @if ($bg
      !=='none' ) skybox-image="storage/backgrounds/<?= $bg ?>" @endif>

      <div class="infoBoxMain">
        <div class="iconsBox">
          <div class="ic_box" id="a1" onclick="HideShowDiv(1)">
            <img src="{{asset('icons/crossSection.png')}}" alt="crossSection">
          </div>
          <div class="ic_box" id="a1" onclick="HideShowDiv(2)">
            <img src="{{asset('icons/annotation.png')}}" alt="annotation">
          </div>
          <div class="ic_box" id="a1" onclick="HideShowDiv(3)">
            <img src="{{asset('icons/download.png')}}" alt="download">
          </div>
        </div>
        <div id="cross-section" class="infoShow" style="display:none">
          <h2>Cross Section</h2>
        </div>
        <div id="annotation" class="infoShow" style="display:none">
          <h2>Annotations</h2>
        </div>
        <div id="download" class="infoShow" style="display:none">
          <h2>Export Image</h2>
        </div>
      </div>

    </model-viewer>

    <script type="module">
    import * as THREE from "https://unpkg.com/three@0.123.0/build/three.module.js"
    import {
      GUI
    } from 'https://threejs.org/examples/jsm/libs/dat.gui.module.js';
    let mixer;
    let scene, model;

    const mv = document.querySelector("model-viewer");

    mv.addEventListener('load', () => {

      let sceneObj = Object.getOwnPropertySymbols(mv).find(
        x => x.description === "scene"
      );
      scene = mv[sceneObj];
      console.log("scene", scene);
      // console.log("mesh?", Object.keys(scene.children));
      console.log("mesh?", scene.children[4]);
      // let mesh = scene.children[4].children

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

      let planes = [
        new THREE.Plane(new THREE.Vector3(-1, 0, 0), 50),
        new THREE.Plane(new THREE.Vector3(0, -1, 0), 12),
        new THREE.Plane(new THREE.Vector3(0, 0, -1), 50)
      ];

      let planeHelpers = planes.map(p => new THREE.PlaneHelper(p, 50, 0xffffff));
      planeHelpers.forEach(ph => {
        ph.visible = false;
        scene.add(ph);
      });


      let object = new THREE.Group();
      object.scale.set(20, 20, 20);
      scene.add(object);

      var gui = new GUI();
      var planeX = gui.addFolder('planeX');
      planeX.add(params.planeX, 'displayHelper').onChange(v => planeHelpers[0]
        .visible = v);
      planeX.add(params.planeX, 'constant').min(-50).max(50).onChange(d => planes[0].constant =
        d);
      planeX.add(params.planeX, 'negated').onChange(() => {

        planes[0].negate();
        params.planeX.constant = planes[0].constant;

      });
      planeX.open();

      var planeY = gui.addFolder('planeY');
      planeY.add(params.planeY, 'displayHelper').onChange(v => planeHelpers[1]
        .visible = v);
      planeY.add(params.planeY, 'constant').min(-50).max(50).onChange(d => planes[1].constant =
        d);
      planeY.add(params.planeY, 'negated').onChange(() => {

        planes[1].negate();
        params.planeY.constant = planes[1].constant;

      });
      planeY.open();

      var planeZ = gui.addFolder('planeZ');
      planeZ.add(params.planeZ, 'displayHelper').onChange(v => planeHelpers[2]
        .visible = v);
      planeZ.add(params.planeZ, 'constant').min(-50).max(50).onChange(d => planes[2].constant =
        d);
      planeZ.add(params.planeZ, 'negated').onChange(() => {

        planes[2].negate();
        params.planeZ.constant = planes[2].constant;

      });
      planeZ.open();

      gui.open();

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

      } // end createPlaneStencilGroup

      mesh = scene.children[0];

      planeObjects = [];
      var planeGeom = new THREE.PlaneBufferGeometry(100, 100);
      for (var i = 0; i < 3; i++) {

        var poGroup = new THREE.Group();
        var plane = planes[i];
        var stencilGroup = createPlaneStencilGroup(mesh.geometry, plane, i + 1);

        // plane is clipped by the other clipping planes
        var planeMat =
          new THREE.MeshStandardMaterial({

            color: 0xE91E63,
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

      // add the color
      var clippedColorFront = new THREE.Mesh(mesh.geometry, material);
      clippedColorFront.castShadow =
        true;
      clippedColorFront.renderOrder = 6;
      object.add(clippedColorFront);

      // scene.add(mesh);
      mesh.scale.set(10, 10, 10);

      function animate() {
        if (planeObjects && planeObjects.length > 0) {
          for (var i = 0; i < planeObjects.length; i++) {

            var plane = planes[i];
            var po = planeObjects[i];
            plane.coplanarPoint(po.position);
            po.lookAt(
              po.position.x - plane.normal.x,
              po.position.y - plane.normal.y,
              po.position.z - plane.normal.z,
            );

          }
        }

      }

    }, true);


    function HideShowDiv(id) {
      if (id == 1) {
        $("#cross-section").show();
        $("#download, #annotation").hide();
        if ($('#cross-section').hasClass('active')) {
          $('#cross-section').animate({
            'right': '-201'
          });
          $('#cross-section, #a1').removeClass('active');
          $(".infoBoxMain").removeClass('info_active');
        } else {
          $('#cross-section').animate({
            'right': '0'
          });
          $('#cross-section').addClass('active');
          $('#annotation, #download').removeClass('active');
          $(".infoBoxMain").addClass('info_active');
        }
      }
      if (id == 2) {
        $("#annotation").show();
        $("#cross-section, #download").hide();
        if ($('#annotation').hasClass('active')) {
          $('#annotation').animate({
            'right': '-201'
          });
          $('#annotation, #a1').removeClass('active');
          $(".infoBoxMain").removeClass('info_active');
        } else {
          $('#annotation').animate({
            'right': '0'
          });
          $('#annotation').addClass('active');
          $('#cross-section, #download').removeClass('active');
          $(".infoBoxMain").addClass('info_active');
        }
      }
      if (id == 3) {
        $("#download").show();
        $("#cross-section, #annotation").hide();
        if ($('#download').hasClass('active')) {
          $('#download').animate({
            'right': '-201'
          });
          $('#download, #a1').removeClass('active');
          $(".infoBoxMain").removeClass('info_active');
        } else {
          $('#download').animate({
            'right': '0'
          });
          $('#download').addClass('active');
          $('#annotation, #cross-section').removeClass('active');
          $(".infoBoxMain").addClass('info_active');
        }
      }
    }
    </script>
  </div>
</body>

</html>