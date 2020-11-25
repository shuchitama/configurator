<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Three</title>
</head>

<body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/102/three.js"></script>
  Three model here - <?= $name ?>
  <script>
  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
  camera.position.z = 5;
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


  let geometry = new THREE.BoxGeometry(1, 1, 1);
  let material = new THREE.MeshLambertMaterial({
    color: 0xF7F7F7
  });
  // let mesh = new THREE.Mesh(geometry, material);

  // scene.add(mesh)

  meshX = -10
  for (let i = 0; i < 10; i++) {
    let mesh = new THREE.Mesh(geometry, material);
    mesh.position.x = (Math.random() - 0.5) * 10;
    mesh.position.y = (Math.random() - 0.5) * 10;
    mesh.position.z = (Math.random() - 0.5) * 10;
    scene.add(mesh);
    meshX += 1;
  }

  let light = new THREE.PointLight(0xFFFFFF, 1, 1000)
  light.position.set(0, 0, 0);
  scene.add(light)

  light = new THREE.PointLight(0xFFFFFF, 2, 1000)
  light.position.set(0, 0, 25);
  scene.add(light)

  const render = function() {
    requestAnimationFrame(render);
    renderer.render(scene, camera);
  }

  // function onMouseMove(event) {
  //   event.preventDefault();

  //   mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
  //   mouse.y = - (event.clientY / window.innerHeight) * 2 + 1;

  //   raycaster.setFromCamera(mouse, camera);

  //   const intersects = raycaster.intersectObjects(scene.children, true);
  //   for (let i = 0; i < intersects.length; i++) {
  //     // intersects[i].object.material.color.set(0xff0000)
  //     this.tl = new TimelineMax();
  //     this.tl.to(intersects[i].object.scale, 1, { x: 2, ease: Expo.easeOut })
  //     this.tl.to(intersects[i].object.scale, 0.5, { x: .5, ease: Expo.easeOut })
  //     this.tl.to(intersects[i].object.position, .5, { x: 2, ease: Expo.easeOut })
  //     this.tl.to(intersects[i].object.rotation, .5, { y: Math.PI * .5, ease: Expo.easeOut }, "=-1.5")
  //   }
  // }

  render();


  window.addEventListener('mousemove', onMouseMove)
  </script>
</body>

</html>