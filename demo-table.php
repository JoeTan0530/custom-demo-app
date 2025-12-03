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
	            	console.log(result);
	            	processLogout(result.data);
	            }
	        }

	        function processLogout(token) {
	        	if (token) {
	                fetch(`https://accounts.google.com/o/oauth2/revoke?token=${token}`, {
			            mode: 'no-cors' // Important for revocation
			        }).then(() => {
			            console.log('Token revoked');
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
				targets.pyramid = generatePyramidPositions(objects, targets);
				// generateUniformPyramid(objects, targets);

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

			function generatePyramidPositions(objects, targets) {
			    const pyramid = [];
			    const vector = new THREE.Vector3();
			    
			    // Calculate base size based on number of objects
			    // For a pyramid, total objects = 1 + 2 + 3 + ... + n
			    // We need to find n where n(n+1)/2 >= objects.length
			    let baseSize = 1;
			    while (baseSize * (baseSize + 1) / 2 < objects.length) {
			        baseSize++;
			    }
			    
			    let objectIndex = 0;
			    const spacing = 200; // Distance between objects
			    const heightSpacing = 250; // Vertical spacing between layers
			    
			    // Create pyramid layers from bottom to top
			    for (let layer = 0; layer < baseSize && objectIndex < objects.length; layer++) {
			        const currentLayerSize = baseSize - layer;
			        const startX = -(currentLayerSize - 1) * spacing / 2;
			        const startZ = -(currentLayerSize - 1) * spacing / 2;
			        const yPos = layer * heightSpacing;

			        console.log("Layer " + layer);
			        
			        // Create objects in current layer
			        for (let x = 0; x < currentLayerSize && objectIndex < objects.length; x++) {
			            for (let z = 0; z < currentLayerSize && objectIndex < objects.length; z++) {
			                const object = new THREE.Object3D();
			                
			                // Calculate position
			                object.position.x = startX + x * spacing;
			                object.position.y = yPos;
			                object.position.z = startZ + z * spacing;
			                
			                // Make objects face outward from pyramid center
			                vector.set(0, yPos, 0); // Point toward the vertical axis
			                object.lookAt(vector);
			                
			                pyramid.push(object);
			                objectIndex++;

			                console.log("Object z: " + z);
			            }
			            console.log("Object x: " + x);
			        }
			    }
			    
			    return pyramid;
			}

			// Alternative version if you want to integrate directly in your loop style:
			function generatePyramidDirect(objects, targets) {
			    targets.pyramid = [];
			    const vector = new THREE.Vector3();
			    
			    let objectIndex = 0;
			    const totalObjects = objects.length;
			    const spacing = 2000;
			    const heightSpacing = 2500;
			    
			    // Find optimal base size for pyramid
			    let baseSize = Math.ceil((Math.sqrt(1 + 8 * totalObjects) - 1) / 2);
			    
			    for (let layer = 0; layer < baseSize && objectIndex < totalObjects; layer++) {
			        const layerSize = baseSize - layer;
			        const halfWidth = (layerSize - 1) * spacing * 0.5;
			        const yPos = layer * heightSpacing;
			        
			        for (let i = 0; i < layerSize * layerSize && objectIndex < totalObjects; i++) {
			            const x = i % layerSize;
			            const z = Math.floor(i / layerSize);
			            
			            const object = new THREE.Object3D();
			            object.position.x = x * spacing - halfWidth;
			            object.position.y = yPos;
			            object.position.z = z * spacing - halfWidth;
			            
			            // Optional: Make objects face outward from center
			            // vector.set(0, yPos, 0);
			            // object.lookAt(vector);
			            
			            targets.pyramid.push(object);
			            objectIndex++;
			        }
			    }
			}

			// For a more uniform pyramid (each layer has decreasing size by 1)
			function generateUniformPyramid(objects, targets) {
			    targets.pyramid = [];
			    const vector = new THREE.Vector3();
			    
			    let objectIndex = 0;
			    const spacing = 1800;
			    const heightSpacing = 2200;
			    let layer = 0;
			    
			    while (objectIndex < objects.length) {
			        const layerSize = Math.floor(objects.length / 5) - layer + 1;
			        if (layerSize <= 0) break;
			        
			        const halfWidth = (layerSize - 1) * spacing * 0.5;
			        const yPos = layer * heightSpacing;
			        
			        for (let x = 0; x < layerSize && objectIndex < objects.length; x++) {
			            for (let z = 0; z < layerSize && objectIndex < objects.length; z++) {
			                const object = new THREE.Object3D();
			                
			                object.position.x = x * spacing - halfWidth;
			                object.position.y = yPos;
			                object.position.z = z * spacing - halfWidth;
			                
			                // Face outward (optional)
			                // const angle = Math.atan2(object.position.x, object.position.z);
			                // object.rotation.y = angle;
			                
			                targets.pyramid.push(object);
			                objectIndex++;
			            }
			        }
			        layer++;
			    }
			}

			function transform( targets, duration ) {

				TWEEN.removeAll();

				for ( let i = 0; i < objects.length; i ++ ) {

					const object = objects[ i ];
					const target = targets[ i ];

					new TWEEN.Tween( object.position )
						.to( { x: target.position.x, y: target.position.y, z: target.position.z }, Math.random() * duration + duration )
						.easing( TWEEN.Easing.Exponential.InOut )
						.start();

					new TWEEN.Tween( object.rotation )
						.to( { x: target.rotation.x, y: target.rotation.y, z: target.rotation.z }, Math.random() * duration + duration )
						.easing( TWEEN.Easing.Exponential.InOut )
						.start();

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