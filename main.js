import './style.css';
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls';
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader';
import { RGBELoader } from 'three/examples/jsm/loaders/RGBELoader';
import { CSS3DRenderer, CSS3DObject } from 'three/examples/jsm/renderers/CSS3DRenderer';



const scene = new THREE.Scene();

// camera
const camera = new THREE.PerspectiveCamera(40, window.innerWidth / window.innerHeight, 0.1, 100);
camera.position.set(0, 2, 3);

// HDR
new RGBELoader()
    .load('texture/rosendal_plains_2_1k.hdr', function(texture) {
        texture.mapping = THREE.EquirectangularReflectionMapping;
        scene.environment = texture;
    });

// Add directional light
const directionalLight = new THREE.DirectionalLight(0xffffff, 1.5);
directionalLight.position.set(0, 10, 0);
directionalLight.castShadow = true;
directionalLight.shadow.mapSize.width = 2048;
directionalLight.shadow.mapSize.height = 2048;
directionalLight.shadow.camera.near = 0.1;
directionalLight.shadow.camera.far = 20;
directionalLight.shadow.camera.left = -10;
directionalLight.shadow.camera.right = 10;
directionalLight.shadow.camera.top = 10;
directionalLight.shadow.camera.right = 10;
directionalLight.shadow.camera.top = 10;
directionalLight.shadow.camera.bottom = -10;
scene.add(directionalLight);

// Create base for car with texture
const baseGeometry = new THREE.BoxGeometry(5, 0.1, 5);
const textureLoader = new THREE.TextureLoader();
const baseTexture = textureLoader.load('texture/Free-Seamless-Tile-Textures.jpg');
baseTexture.wrapS = THREE.RepeatWrapping;
baseTexture.wrapT = THREE.RepeatWrapping;
baseTexture.repeat.set(1, 1);
const baseMaterial = new THREE.MeshStandardMaterial({ 
    map: baseTexture,
    roughness: 1,
    metalness: 1
});
const base = new THREE.Mesh(baseGeometry, baseMaterial);
base.position.y = -0.05;
base.receiveShadow = true;
scene.add(base);

// Create floating H1 text
const h1Text = document.createElement('h1');
h1Text.textContent = 'Tesla Roadster';
h1Text.style.color = 'white';
h1Text.style.fontFamily = 'Arial, sans-serif';
h1Text.style.fontSize = '4em';
h1Text.style.textShadow = '2px 2px 4px rgba(0,0,0,0.5)';
h1Text.style.padding = '20px';
h1Text.style.pointerEvents = 'none';
h1Text.style.antialias = true;

// Add textured background to H1
const textureUrl = 'texture/Free-Seamless-Tile-Textures.jpg';
h1Text.style.background = `url(${textureUrl})`;
h1Text.style.backgroundSize = 'cover';
h1Text.style.backgroundClip = 'text';
h1Text.style.webkitBackgroundClip = 'text';
h1Text.style.webkitTextFillColor = 'transparent';
h1Text.style.borderRadius = '10px';

// Create CSS3D object
const css3DObject = new CSS3DObject(h1Text);
css3DObject.position.set(0, 1, -2);
css3DObject.scale.set(0.01, 0.01, 0.01);
css3DObject.rotation.y = 0;
scene.add(css3DObject);

// CSS3D renderer
const css3DRenderer = new CSS3DRenderer();
css3DRenderer.setSize(window.innerWidth, window.innerHeight);
css3DRenderer.domElement.style.position = 'absolute';
css3DRenderer.domElement.style.top = '0';
css3DRenderer.domElement.style.pointerEvents = 'none';
document.body.appendChild(css3DRenderer.domElement);

let carModel1;
let carModel2;
let currentCar;
let carBody;
let carScale = 0.5;

// Get navigation buttons and set z-index
const nextButton = document.querySelector('.first_container button:last-child');
const backButton = document.querySelector('.first_container button:nth-last-child(2)');
const colorPanel = document.querySelector('.color-panel');
const colorPanelBtn = document.getElementById('colorPanelBtn');

// Set high z-index for buttons and panels
nextButton.style.zIndex = '1000';
backButton.style.zIndex = '1000';
colorPanel.style.zIndex = '1000';
colorPanelBtn.style.zIndex = '1000';

// Initially hide back button since we start with first model
backButton.style.display = 'none';

// Function to setup car model
function setupCarModel(model) {
    model.traverse((child) => {
        if (child.isMesh) {
            child.castShadow = true;
            if (child.material) {
                child.material = child.material.clone();
            }
        }
    });
}

// Load first car model (Tesla Roadster)
const loader = new GLTFLoader();
loader.load(
    'texture/tesla_roadster_2020/scene.gltf',
    function (gltf) {
        carModel1 = gltf.scene;
        scene.add(carModel1);
        carModel1.scale.set(carScale, carScale, carScale);
        carModel1.position.set(0, 0, 0);
        currentCar = carModel1;
        setupCarModel(carModel1);
    }
);

// Load second car model
loader.load(
    'texture/tesla_model_3/scene.gltf',
    function (gltf) {
        carModel2 = gltf.scene;
        carModel2.scale.set(0.005, 0.005, 0.005);
        carModel2.rotation.y = Math.PI;
        carModel2.position.set(0, 0, 0);
        carModel2.visible = false;
        scene.add(carModel2);
        setupCarModel(carModel2);
    }
);

// Animation for model transition
function transitionModels(fromModel, toModel) {
    const duration = 1000;
    const startOpacity = 1;
    const endOpacity = 0;
    const startTime = Date.now();

    function animate() {
        const currentTime = Date.now();
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        if (fromModel) {
            fromModel.traverse((child) => {
                if (child.isMesh) {
                    if (!child.material.transparent) {
                        child.material.transparent = true;
                    }
                    child.material.opacity = startOpacity * (1 - progress);
                }
            });
        }

        if (progress === 1) {
            if (fromModel) fromModel.visible = false;
            toModel.visible = true;
            toModel.traverse((child) => {
                if (child.isMesh) {
                    child.material.transparent = true;
                    child.material.opacity = 0;
                }
            });

            // Fade in animation for new model
            const fadeInStart = Date.now();
            function fadeIn() {
                const fadeProgress = Math.min((Date.now() - fadeInStart) / duration, 1);
                toModel.traverse((child) => {
                    if (child.isMesh) {
                        child.material.opacity = fadeProgress;
                    }
                });

                if (fadeProgress < 1) {
                    requestAnimationFrame(fadeIn);
                }
            }
            fadeIn();
            return;
        }
        requestAnimationFrame(animate);
    }
    animate();
}

// Next button click handler
nextButton.addEventListener('click', (e) => {
    e.preventDefault();
    if (currentCar === carModel1) {
        transitionModels(carModel1, carModel2);
        currentCar = carModel2;
        h1Text.textContent = 'Tesla Model 3';
        
        document.querySelectorAll('.color-option').forEach((option, index) => {
            if (index >= 4) {
                option.style.display = 'none';
            }
        });

        nextButton.style.display = 'none';
        backButton.style.display = 'block';
    }
});

// Back button click handler
backButton.addEventListener('click', (e) => {
    e.preventDefault();
    if (currentCar === carModel2) {
        transitionModels(carModel2, carModel1);
        currentCar = carModel1;
        h1Text.textContent = 'Tesla Roadster';
        
        document.querySelectorAll('.color-option').forEach(option => {
            option.style.display = 'block';
        });

        backButton.style.display = 'none';
        nextButton.style.display = 'block';
    }
});

// Function to change car color
function changeColors(color) {
    if (currentCar) {
        currentCar.traverse((child) => {
            if (child.isMesh) {
                if (currentCar === carModel2) {
                    const name = (child.material?.name || child.name || '').toLowerCase();
                    if (name.includes('body') || !name.includes('door') || name.includes('car')) {
                        if (Array.isArray(child.material)) {
                            child.material.forEach(mat => {
                                mat.color = new THREE.Color(color);
                            });
                        } else {
                            child.material.color = new THREE.Color(color);
                        }
                    }
                } else {
                    const name = (child.material?.name || child.name || '').toLowerCase();
                    if (name.includes('body') || name.includes('door') || name.includes('car')) {
                        if (Array.isArray(child.material)) {
                            child.material.forEach(mat => {
                                mat.color = new THREE.Color(color);
                            });
                        } else {
                            child.material.color = new THREE.Color(color);
                        }
                    }
                }
            }
        });
    }
}

// Color panel event listeners
const colors = [
    '#FFB6C1', '#FF00FF', '#00FFFF', '#FFFFFF', '#000000',
    '#FF0000', '#00FF00', '#0000FF', '#FFA500', '#800080',
    '#008000', '#800000', '#808080', '#C0C0C0', '#FFFF00'
];

document.querySelector('.color-panel').querySelectorAll('.color-option').forEach((option, index) => {
    option.style.zIndex = '1000';
    option.addEventListener('click', function() {
        const color = colors[index];
        changeColors(color);
    });
});

// Color panel button event listener
document.getElementById('colorPanelBtn').addEventListener('click', function() {
    const colorPanel = document.querySelector('.color-panel');
    if (colorPanel.style.display === 'none' || !colorPanel.style.display) {
        colorPanel.style.display = 'flex';
    } else {
        colorPanel.style.display = 'none';
    }
});

// renderer
const renderer = new THREE.WebGLRenderer({
    canvas: document.querySelector('canvas'),
    antialias: true,
});
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1));
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 1;
renderer.outputEncoding = THREE.sRGBEncoding;
renderer.shadowMap.enabled = true;

const pmremGenerator = new THREE.PMREMGenerator(renderer);
pmremGenerator.compileEquirectangularShader();

// controls
const controls = new OrbitControls(camera, renderer.domElement);
controls.enableDamping = true;
controls.minDistance = 1;
controls.maxDistance = 10;
controls.minPolarAngle = 0;
controls.maxPolarAngle = Math.PI / 2;
controls.target.set(0, 0, 0);

// Handle window resize
window.addEventListener('resize', () => {
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
    css3DRenderer.setSize(window.innerWidth, window.innerHeight);
});

// Prevent scrollbar by setting overflow to hidden
document.body.style.overflow = 'hidden';

// render
renderer.render(scene, camera);

function animate() {
    window.requestAnimationFrame(animate);
    controls.update();
    renderer.render(scene, camera);
    css3DRenderer.render(scene, camera);
}
animate();
