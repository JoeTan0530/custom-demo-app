<?php 
	session_start(); 

	if (!isset($_SESSION['google_user']) && !isset($_SESSION['google_token'])) {
		header('Location: index.php');
	}
?>
<?php include "sheetsLoader.php";?>
<?php 
	$tableStructureObj = [
		[
			"rowNum" => 1,
			"dataPerRow" => 4,
			"currentDataPerRow" => 0,
			"emptyColumns" => 16,
			"leftItemCount" => [
				"total" => 2,
				"current" => 0
			],
			"rightItemCount" => [
				"total" => 2,
				"current" => 0
			],
		],
		[
			"rowNum" => 2,
			"dataPerRow" => 10,
			"currentDataPerRow" => 0,
			"emptyColumns" => 10,
			"leftItemCount" => [
				"total" => 3,
				"current" => 0
			],
			"rightItemCount" => [
				"total" => 7,
				"current" => 0
			],
		],
		[
			"rowNum" => 3,
			"dataPerRow" => 10,
			"currentDataPerRow" => 0,
			"emptyColumns" => 10,
			"leftItemCount" => [
				"total" => 3,
				"current" => 0
			],
			"rightItemCount" => [
				"total" => 7,
				"current" => 0
			],
		],
		[
			"rowNum" => 4,
			"dataPerRow" => 20,
			"currentDataPerRow" => 0,
		],
		[
			"rowNum" => 5,
			"dataPerRow" => 20,
			"currentDataPerRow" => 0 
		],
		[
			"rowNum" => 6,
			"dataPerRow" => 19,
			"currentDataPerRow" => 0,
			"emptyColumns" => 1,
			"leftItemCount" => [
				"total" => 3,
				"current" => 0
			],
			"rightItemCount" => [
				"total" => 16,
				"current" => 0
			],
		],
		[
			"rowNum" => 7,
			"dataPerRow" => 19,
			"currentDataPerRow" => 0,
			"emptyColumns" => 1,
			"leftItemCount" => [
				"total" => 3,
				"current" => 0
			],
			"rightItemCount" => [
				"total" => 16,
				"current" => 0
			], 
		],
		[
			"rowNum" => "default",
			"dataPerRow" => 16,
			"currentDataPerRow" => 0,
			"latestRow" => 9,
			"emptyColumns" => 3
		],
	];

	$tableData = array();
	if ($data) {
		foreach($data as $dataKey => $dataItem) {
			$tempNetWorth = $dataItem[5];
			$tempNetWorth = str_replace("$", "", $tempNetWorth);
			$tempNetWorth = explode(",", $tempNetWorth);
			$tempNetWorth = implode("", $tempNetWorth);

			$position = getDisplayPosition();

			$tempItem = [
				$dataItem[0], //name
				$dataItem[1], //photo
				$dataItem[2], //age
				$dataItem[3], //country
				$dataItem[4], //interest
				(float)$tempNetWorth, //net worth
				$position['x'],
				$position['y'],
			];

			$tableData = array_merge($tableData, $tempItem);
		}
	}

	function getDisplayPosition() {
		global $tableStructureObj;

		$tempObj = $tableStructureObj;

		$position = [
			"x" => 1,
			"y" => 1
		];

		foreach($tempObj as $tableStructureObjKey => $tableStructureObjItem) {
			if ($tableStructureObjItem['rowNum'] != "default" && $tableStructureObjItem['currentDataPerRow'] < $tableStructureObjItem['dataPerRow']) {
				$position['y'] = $tableStructureObjItem['rowNum'];
				$xPosition = 0;

				if (isset($tableStructureObjItem['leftItemCount']) || isset($tableStructureObjItem['rightItemCount'])) {
					$isInLeft = false;
					if ($tableStructureObjItem['leftItemCount'] && ($tableStructureObjItem['leftItemCount']['current'] < $tableStructureObjItem['leftItemCount']['total'])) {
						$xPosition = (int)$tableStructureObjItem['leftItemCount']['current'] + 1;

						$tableStructureObj[$tableStructureObjKey]['leftItemCount']['current'] = $xPosition;
						$isInLeft = true;
					}

					if ($tableStructureObjItem['rightItemCount'] && ($tableStructureObjItem['rightItemCount']['current'] < $tableStructureObjItem['rightItemCount']['total']) && !$isInLeft) {
						$xPosition = (int)$tableStructureObjItem['rightItemCount']['current'] + 1 + $tableStructureObjItem['emptyColumns'] + (int)$tableStructureObjItem['leftItemCount']['total'];

						$tableStructureObj[$tableStructureObjKey]['rightItemCount']['current'] = (int)$tableStructureObjItem['rightItemCount']['current'] + 1;
					}
				} else {
					$xPosition = $tableStructureObjItem['currentDataPerRow'] + 1;
				}

				$tableStructureObj[$tableStructureObjKey]['currentDataPerRow'] = $tableStructureObjItem['currentDataPerRow'] + 1;
				$position['x'] = $xPosition;
				break;
			} else if ($tableStructureObjItem['rowNum'] == "default") {
				$position['y'] = $tableStructureObjItem['latestRow'];

				$position['x'] = (int)$tableStructureObjItem['currentDataPerRow'] + 1 + $tableStructureObjItem['emptyColumns'];

				if ($tableStructureObjItem['currentDataPerRow'] < $tableStructureObjItem['dataPerRow']) {
					$tableStructureObj[$tableStructureObjKey]['currentDataPerRow'] = (int)$tableStructureObjItem['currentDataPerRow'] + 1;
				} else {
					$tableStructureObj[$tableStructureObjKey]['currentDataPerRow'] = 0;
					$tableStructureObj[$tableStructureObjKey]['latestRow'] = $tableStructureObjItem['latestRow'] + 1;
				}
			}
		}

		return $position;
	}
?>
<?php include "./assets/config/config.php"?>

<!DOCTYPE html>
<html>
	<?php include "head.php"?>
	<style>
		body {
			background-color: #000000;
		}

		a, h1, h2, .display-text {
			color: #8ff;
		}

		.font-weight-thick {
			font-weight: 600;
		}

		h1 {
			font-size: 30px;
			font-weight: 400;
		}

		h2 {
			font-size: 20px;
			font-weight: 400;
		}

		#menu {
			position: absolute;
			bottom: 20px;
			width: 100%;
			text-align: center;
		}

		.net-worth-container {
			position: absolute;
			width: 500px;
			bottom: -14px;
    		right: 430px;
			transform: translate(50%, -50%);
		}

		.net-worth-bar {
			width: 90%;
			background-image: linear-gradient(to right, rgb(239, 48, 34) 0%, rgb(253, 202, 53) 50%, rgb(58, 159, 72) 100%);
			height: 30px;
		}

		.element {
			width: 120px;
			height: 170px;
			box-shadow: 0px 0px 12px rgba(0,255,255,0.5);
			border: 1px solid rgba(127,255,255,0.25);
			font-family: Helvetica, sans-serif;
			text-align: center;
			line-height: normal;
			cursor: default;
			background-color: rgba(0,127,127, 0.5);
		}

		.element.high-net-worth {
			background-color: rgba(58, 159, 72, 0.5);
		}

		.element.middle-net-worth {
			background-color: rgba(253, 202, 53, 0.5);
		}

		.element.low-net-worth {
			background-color: rgba(239, 48, 34, 0.5);
		}

		.element:hover {
			box-shadow: 0px 0px 12px rgba(0,255,255,0.75);
			border: 1px solid rgba(127,255,255,0.75);
		}

		.element .number {
			position: absolute;
			top: 10px;
			right: 5px;
			font-size: 12px;
			color: rgba(127,255,255,0.75);
			display: flex; 
			justify-content: space-between;
			align-items: center;
			width: 90%;
		}

		.element .symbol {
			position: absolute;
			top: 30px;
			left: 0px;
			right: 0px;
			font-size: 60px;
			font-weight: bold;
			color: rgba(255,255,255,0.75);
			text-shadow: 0 0 10px rgba(0,255,255,0.95);
			padding: 0rem 1rem;
		}

		.element .details {
			position: absolute;
			bottom: 8px;
			left: 0px;
			right: 0px;
			font-size: 12px;
			color: rgba(127,255,255,0.75);
		}

		button {
			color: rgba(127,255,255,0.75);
			background: transparent;
			outline: 1px solid rgba(127,255,255,0.75);
			border: 0px;
			padding: 5px 10px;
			cursor: pointer;
		}

		button:hover {
			background-color: rgba(0,255,255,0.5);
		}

		button:active {
			color: #000000;
			background-color: rgba(0,255,255,0.75);
		}

		@media (min-width: 991px) and (max-width: 1199px) {
			.net-worth-container {
				width: 450px;
			    bottom: 44px;
			    right: 511px;
			}
		}

		@media (min-width: 768px) and (max-width: 990px) {
			.net-worth-container {
			    width: 450px;
			    bottom: 44px;
			    right: 381px;
			}
		}

		@media (min-width: 320px) and (max-width: 767px) {
			.net-worth-container {
		        width: 300px;
			    bottom: 40px;
			    right: 184px;
			}

			.net-worth-bar {
				height: 20px;
			}
		}
	</style>
	<body>
		<div class="d-flex flex-column py-2 px-4">
			<h1 class="mb-1">
				Welcome, <span class="font-weight-thick"><?php echo $_SESSION['google_user']['name'];?></span>
			</h1>
			<h2 class="mb-2">
				Email: <span class="font-weight-thick"><?php echo $_SESSION['google_user']['email'];?></span>
			</h2>
			<a href="javascript:void(0);" onclick="handleLogout();">Logout</a>
		</div>
		<div id="container"></div>
		<div id="menu">
			<button id="table">TABLE</button>
			<button id="sphere">SPHERE</button>
			<button id="helix">DOUBLE HELIX</button>
			<button id="grid">GRID</button>
			<button id="pyramid">PYRAMID</button>
		</div>
		<div class="net-worth-container">
			<div class="d-flex flex-column justify-content-center align-items-center">
				<p class="display-text mb-1 mb-md-2">
					Net Worth
				</p>
				<div class="d-flex justify-content-center align-items-center w-100">
					<p class="display-text mb-0 me-3">Low</p>
					<div class="net-worth-bar"></div>
					<p class="display-text mb-0 ms-3">High</p>
				</div>
			</div>
		</div>

		<script>
			// Handle sign out
	        async function handleLogout() {
	            const formData = new FormData();
	            formData.append('performFunction', "logoutGoogle");

	            const response = await fetch('logoutGoogle.php', {
	                method: 'POST',
	                body: formData
	            });

	            if (response.ok) {
	            	const result = await response.json();
	            	
	            	processLogout(result.data);
	            }
	        }

	        function processLogout(token) {
	        	if (token) {
	                fetch(`https://accounts.google.com/o/oauth2/revoke?token=${token}`, {
			            mode: 'no-cors' // Important for revocation
			        }).then(() => {
			            // console.log('Token revoked');
			            logoutFallback();
			        }).catch(error => {
			            console.log('Revocation error:', error);
			        });
	            }
	        }

	        function logoutFallback() {
	        	window.location.href = "index.php";
	        }

		</script>

		<script type="importmap">
			{
				"imports": {
					"three": "./build/three.module.js",
					"three/addons/": "./jsm/"
				}
			}
		</script>

		<script type="module">

			import * as THREE from 'three';

			import TWEEN from 'three/addons/libs/tween.module.js';
			import { TrackballControls } from 'three/addons/controls/TrackballControls.js';
			import { CSS3DRenderer, CSS3DObject } from 'three/addons/renderers/CSS3DRenderer.js';

			let camera, scene, renderer;
			let controls;

			const objects = [];
			const targets = { table: [], sphere: [], helix: [], grid: [], pyramid: [] };
			const table = <?php echo json_encode(($tableData ? $tableData : []));?>;

			init();
			animate();

			function init() {

				camera = new THREE.PerspectiveCamera( 40, window.innerWidth / window.innerHeight, 1, 10000 );
				camera.position.z = 3000;

				scene = new THREE.Scene();

				const netWorthClassArr = [
					{
						fromAmount: 0,
						toAmount: 100000,
						class: "low"
					},
					{
						fromAmount: 100000,
						toAmount: 200000,
						class: "middle"
					},
					{
						fromAmount: 200000,
						toAmount: "all",
						class: "high"
					},
				]

				// table
				for ( let i = 0; i < table.length; i += 8 ) {
					let netWorthClass = "";
					let netWorth = table[i + 5];

					netWorthClassArr.forEach((value, index) => {
						if (netWorth >= value['fromAmount'] && (value['toAmount'] != "all" ? netWorth < value['toAmount'] : true)) {
							netWorthClass = value['class'];
						}
					});

					const element = document.createElement( 'div' );
					element.className = `element ${netWorthClass}-net-worth`;

					const number = document.createElement( 'div' );
					number.className = 'number';
					number.innerHTML = `<span>${table[i + 3]}</span><span>${table[i + 2]}</span>`;
					element.appendChild( number );

					const symbol = document.createElement( 'div' );
					symbol.className = 'symbol w-100';
					symbol.innerHTML = `<img src="${table[i + 1]}" style="width: 100%; height: auto;"/>`;
					element.appendChild( symbol );

					const details = document.createElement( 'div' );
					details.className = 'details';
					details.innerHTML = table[i] + '<br>' + table[i + 4];
					element.appendChild( details );

					const objectCSS = new CSS3DObject( element );
					objectCSS.position.x = Math.random() * 4000 - 2000;
					objectCSS.position.y = Math.random() * 4000 - 2000;
					objectCSS.position.z = Math.random() * 4000 - 2000;
					scene.add( objectCSS );

					objects.push( objectCSS );

					const object = new THREE.Object3D();
					object.position.x = ( table[ i + 6 ] * 140 ) - 1330;
					object.position.y = - ( table[ i + 7 ] * 180 ) + 990;

					targets.table.push( object );

				}

				// sphere

				const vector = new THREE.Vector3();

				for ( let i = 0, l = objects.length; i < l; i ++ ) {

					const phi = Math.acos( - 1 + ( 2 * i ) / l );
					const theta = Math.sqrt( l * Math.PI ) * phi;

					const object = new THREE.Object3D();

					object.position.setFromSphericalCoords( 800, phi, theta );

					vector.copy( object.position ).multiplyScalar( 2 );

					object.lookAt( vector );

					targets.sphere.push( object );

				}

				// Double helix
				for ( let i = 0, l = objects.length; i < l; i ++ ) {

				    const theta = i * 0.175 + Math.PI;
				    const y = - ( i * 40 ) + 450;

				    // First helix strand
				    const object1 = new THREE.Object3D();
				    object1.position.setFromCylindricalCoords( 900, theta, y );
				    
				    vector.x = object1.position.x * 2;
				    vector.y = object1.position.y;
				    vector.z = object1.position.z * 2;
				    object1.lookAt( vector );
				    targets.helix.push( object1 );

				    // Second helix strand (opposite side)
				    const object2 = new THREE.Object3D();
				    object2.position.setFromCylindricalCoords( 900, theta + Math.PI, y ); // 180 degrees offset
				    
				    vector.x = object2.position.x * 2;
				    vector.y = object2.position.y;
				    vector.z = object2.position.z * 2;
				    object2.lookAt( vector );
				    targets.helix.push( object2 );

				}

				// grid
				for ( let i = 0; i < objects.length; i ++ ) {

					const object = new THREE.Object3D();

					object.position.x = ( ( i % 5 ) * 400 ) - 100;
					object.position.y = ( - ( Math.floor( i / 5 ) % 4 ) * 300 ) + 800;
					object.position.z = ( Math.floor( i / 20 ) ) * 1000 - 2000;

					targets.grid.push( object );

				}

				// pyramid
				generateTetrahedronSurface(objects, targets);

				//

				renderer = new CSS3DRenderer();
				renderer.setSize( window.innerWidth, window.innerHeight );
				document.getElementById( 'container' ).appendChild( renderer.domElement );

				//

				controls = new TrackballControls( camera, renderer.domElement );
				controls.minDistance = 500;
				controls.maxDistance = 6000;
				controls.addEventListener( 'change', render );

				const buttonTable = document.getElementById( 'table' );
				buttonTable.addEventListener( 'click', function () {

					transform( targets.table, 2000 );

				} );

				const buttonSphere = document.getElementById( 'sphere' );
				buttonSphere.addEventListener( 'click', function () {

					transform( targets.sphere, 2000 );

				} );

				const buttonHelix = document.getElementById( 'helix' );
				buttonHelix.addEventListener( 'click', function () {

					transform( targets.helix, 2000 );

				} );

				const buttonGrid = document.getElementById( 'grid' );
				buttonGrid.addEventListener( 'click', function () {
					transform( targets.grid, 2000 );

				} );

				const buttonPyramid = document.getElementById( 'pyramid' );
				buttonPyramid.addEventListener( 'click', function () {
					transform( targets.pyramid, 2000 );

				} );

				transform( targets.table, 2000 );

				//

				window.addEventListener( 'resize', onWindowResize );

			}

			// Generate Pyramid (Tetrahedron) shape positions
			function generateTetrahedronSurface(objects, targets) {
			    targets.pyramid = [];
			    const vector = new THREE.Vector3();
			    
			    // Create basic pyramid geometry (detail=0)
			    // The calculation for radius is to calculate the proper radius for the shape dynamically based on the total records available
			    const radius = (objects.length * 10) / 1.5;
			    const geometry = new THREE.TetrahedronGeometry(radius, 0);
			    
			    // Get vertices - with detail=0, we have exactly 4 vertices
			    const positions = geometry.attributes.position.array;
			    
			    // Define the 4 vertices manually (for detail=0 pyramid)
			    const vertices = [];
			    for (let i = 0; i < 4; i++) {
			        vertices.push(new THREE.Vector3(
			            positions[i * 3],
			            positions[i * 3 + 1],
			            positions[i * 3 + 2]
			        ));
			    }
			    
			    // Manually define the 4 faces (triangles) of a pyramid
			    // Each face is defined by 3 vertices
			    const faces = [
			        { v1: vertices[0], v2: vertices[1], v3: vertices[2] }, // Face 0
			        { v1: vertices[0], v2: vertices[2], v3: vertices[3] }, // Face 1
			        { v1: vertices[0], v2: vertices[3], v3: vertices[1] }, // Face 2
			        { v1: vertices[1], v2: vertices[3], v3: vertices[2] }  // Face 3 (base)
			    ];
			    
			    // Calculate normals for each face
			    faces.forEach(face => {
			        const normal = new THREE.Vector3()
			            .crossVectors(
			                new THREE.Vector3().subVectors(face.v2, face.v1),
			                new THREE.Vector3().subVectors(face.v3, face.v1)
			            )
			            .normalize();
			        face.normal = normal;
			        
			        // Calculate center of face
			        face.center = new THREE.Vector3()
			            .add(face.v1)
			            .add(face.v2)
			            .add(face.v3)
			            .divideScalar(3);
			    });
			    
			    let objectIndex = 0;
			    const totalObjects = objects.length;
			    
			    // Phase 1: Place objects on face interiors (avoid edges)
			    
			    // Calculate how many objects per face (distribute proportionally by area)
			    // For regular pyramid, all faces have equal area
			    const objectsPerFaceBase = Math.floor(totalObjects / 4);
			    const extraObjects = totalObjects % 4;
			    
			    for (let faceIdx = 0; faceIdx < 4 && objectIndex < totalObjects; faceIdx++) {
			        const face = faces[faceIdx];
			        const objectsForThisFace = objectsPerFaceBase + (faceIdx < extraObjects ? 1 : 0);
			        
			        if (objectsForThisFace <= 0) continue;
			        
			        // Create triangular grid inside this face
			        const gridSize = Math.max(2, Math.ceil(Math.sqrt(objectsForThisFace * 2)));
			        
			        for (let i = 1; i < gridSize && objectIndex < totalObjects; i++) {
			            for (let j = 1; j < gridSize - i && objectIndex < totalObjects; j++) {
			                // Barycentric coordinates (avoid edges where i=0, j=0, or i+j=gridSize)
			                const u = i / gridSize;
			                const v = j / gridSize;
			                
			                // Position inside triangle
			                const position = face.v1.clone()
			                    .add(face.v2.clone().sub(face.v1).multiplyScalar(u))
			                    .add(face.v3.clone().sub(face.v1).multiplyScalar(v));
			                
			                const object = new THREE.Object3D();
			                object.position.copy(position);
			                
			                // Face outward along face normal
			                vector.copy(position).add(face.normal.clone().multiplyScalar(100));
			                object.lookAt(vector);
			                
			                targets.pyramid.push(object);
			                objectIndex++;
			            }
			        }
			    }
			    
			    // Phase 2: If we have fewer objects than grid positions, we're done
			    // If we need more objects, add them along edges
			    if (objectIndex < totalObjects) {
			        
			        // Define the 6 edges of the pyramid
			        const edges = [
			            { start: vertices[0], end: vertices[1], faces: [faces[0], faces[2]] },
			            { start: vertices[0], end: vertices[2], faces: [faces[0], faces[1]] },
			            { start: vertices[0], end: vertices[3], faces: [faces[1], faces[2]] },
			            { start: vertices[1], end: vertices[2], faces: [faces[0], faces[3]] },
			            { start: vertices[1], end: vertices[3], faces: [faces[2], faces[3]] },
			            { start: vertices[2], end: vertices[3], faces: [faces[1], faces[3]] }
			        ];
			        
			        // Calculate remaining objects per edge
			        const remainingObjects = totalObjects - objectIndex;
			        const objectsPerEdge = Math.max(1, Math.floor(remainingObjects / 6));
			        
			        for (const edge of edges) {
			            if (objectIndex >= totalObjects) break;
			            
			            const edgeVector = new THREE.Vector3().subVectors(edge.end, edge.start);
			            const edgeLength = edgeVector.length();
			            
			            // Place objects along this edge
			            for (let i = 1; i <= objectsPerEdge && objectIndex < totalObjects; i++) {
			                const t = i / (objectsPerEdge + 1); // Avoid vertices
			                const position = new THREE.Vector3().lerpVectors(edge.start, edge.end, t);
			                
			                const object = new THREE.Object3D();
			                object.position.copy(position);
			                
			                // Calculate normal by averaging the two face normals
			                const avgNormal = new THREE.Vector3()
			                    .add(edge.faces[0].normal)
			                    .add(edge.faces[1].normal)
			                    .normalize();
			                
			                vector.copy(position).add(avgNormal.multiplyScalar(100));
			                object.lookAt(vector);
			                
			                targets.pyramid.push(object);
			                objectIndex++;
			            }
			        }
			    }
			    
			    // Phase 3: Add vertices if still need objects
			    if (objectIndex < totalObjects) {
			        for (const vertex of vertices) {
			            if (objectIndex >= totalObjects) break;
			            
			            // Check if vertex already has an object nearby
			            const hasNearbyObject = targets.pyramid.some(obj => 
			                obj.position.distanceTo(vertex) < 50
			            );
			            
			            if (!hasNearbyObject) {
			                const object = new THREE.Object3D();
			                object.position.copy(vertex);
			                
			                // Face outward from center
			                vector.copy(vertex).multiplyScalar(2);
			                object.lookAt(vector);
			                
			                targets.pyramid.push(object);
			                objectIndex++;
			            }
			        }
			    }
			    
			    // Phase 4: Random points on faces for any remaining objects
			    if (objectIndex < totalObjects) {
			        while (objectIndex < totalObjects) {
			            // Pick random face
			            const face = faces[Math.floor(Math.random() * 4)];
			            
			            // Random barycentric coordinates
			            let r1 = Math.random();
			            let r2 = Math.random();
			            if (r1 + r2 > 1) {
			                r1 = 1 - r1;
			                r2 = 1 - r2;
			            }
			            
			            const position = face.v1.clone()
			                .add(face.v2.clone().sub(face.v1).multiplyScalar(r1))
			                .add(face.v3.clone().sub(face.v1).multiplyScalar(r2));
			            
			            const object = new THREE.Object3D();
			            object.position.copy(position);
			            
			            vector.copy(position).add(face.normal.clone().multiplyScalar(100));
			            object.lookAt(vector);
			            
			            targets.pyramid.push(object);
			            objectIndex++;
			        }
			    }
			}

			function transform( targets, duration ) {
				TWEEN.removeAll();

				for ( let i = 0; i < objects.length; i ++ ) {

					const object = objects[ i ];
					const target = targets[ i ];

					if (object && target) {
						new TWEEN.Tween( object.position )
						.to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
						.easing( TWEEN.Easing.Exponential.InOut )
						.start();

						new TWEEN.Tween( object.rotation )
							.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
							.easing( TWEEN.Easing.Exponential.InOut )
							.start();
					}

				}

				new TWEEN.Tween( this )
					.to( {}, duration * 2 )
					.onUpdate( render )
					.start();

			}

			function onWindowResize() {

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

				render();

			}

			function animate() {

				requestAnimationFrame( animate );

				TWEEN.update();

				controls.update();

			}

			function render() {

				renderer.render( scene, camera );

			}

		</script>
	</body>
</html>