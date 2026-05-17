function init(){
    gsap.registerPlugin(ScrollTrigger);
const locoScroll = new LocomotiveScroll({
  el: document.querySelector("main"),
  smooth: true
});
locoScroll.on("scroll", ScrollTrigger.update);
ScrollTrigger.scrollerProxy("main", {
  scrollTop(value) {
    return arguments.length ? locoScroll.scrollTo(value, 0, 0) : locoScroll.scroll.instance.scroll.y;
  },
  getBoundingClientRect() {
    return {top: 0, left: 0, width: window.innerWidth, height: window.innerHeight};
  },
  pinType: document.querySelector("main").style.transform ? "transform" : "fixed"
});
ScrollTrigger.addEventListener("refresh", () => locoScroll.update());
ScrollTrigger.refresh();
}
init()

function cursorTracer(){
  const cursor = document.createElement('div');
cursor.style.position = 'fixed';
cursor.style.width = '20px'; // Smaller cursor size
cursor.style.height = '20px'; // Smaller cursor size
cursor.style.borderRadius = '50%';
cursor.style.background = 'radial-gradient(circle, #ff0080, #ff8c00, #00ff8c, #008cff, #8000ff, #ff00ff, #00ffff)';
cursor.style.pointerEvents = 'none';
cursor.style.transition = 'transform 0.1s ease';
cursor.style.zIndex = '1000';
document.body.appendChild(cursor);

document.addEventListener('mousemove', (e) => {
    cursor.style.left = `${e.pageX}px`;
    cursor.style.top = `${e.pageY}px`;
    cursor.style.transform = 'translate(-50%, -50%)';
});

document.addEventListener('click', () => {
    for (let i = 0; i < 20; i++) { // Increase the number of particles
        createParticle(event.pageX, event.pageY);
    }
});

function createParticle(x, y) {
    const particle = document.createElement('div');
    particle.style.position = 'absolute';
    particle.style.width = '5px'; // Smaller particle size
    particle.style.height = '5px'; // Smaller particle size
    particle.style.borderRadius = '50%';
    particle.style.background = `hsl(${Math.random() * 360}, 100%, 50%)`;
    particle.style.pointerEvents = 'none';
    particle.style.left = `${x}px`;
    particle.style.top = `${y}px`;
    document.body.appendChild(particle);

    const animationDuration = Math.random() * 1 + 0.5;
    particle.animate([
        { transform: 'translate(-50%, -50%) scale(1)' },
        { transform: 'translate(-50%, -50%) scale(2)', opacity: 0 }
    ], {
        duration: animationDuration * 1000,
        easing: 'ease-out',
        fill: 'forwards'
    });

    setTimeout(() => {
        particle.remove();
    }, animationDuration * 1000);
}
const particles = [];



for (let i = 0; i < 10; i++) {
    const smallParticle = document.createElement('div');
    smallParticle.style.position = 'fixed';
    smallParticle.style.width = '10px';
    smallParticle.style.height = '10px';
    smallParticle.style.borderRadius = '50%';
    smallParticle.style.background = `hsl(${Math.random() * 360}, 100%, 50%)`;
    smallParticle.style.pointerEvents = 'none';
    smallParticle.style.zIndex = '999';
    smallParticle.style.filter = `blur(${i * 0.4}px)`; // Increase blur for farther particles
    document.body.appendChild(smallParticle);
    particles.push(smallParticle);
}

document.addEventListener('mousemove', (e) => {
    particles.forEach((particle, index) => {
        setTimeout(() => {
            particle.style.left = `${e.pageX}px`;
            particle.style.top = `${e.pageY}px`;
            particle.style.transform = 'translate(-50%, -50%)';
        }, index * 50); // Delay each particle's movement
    });
});

}
cursorTracer();

function steering(){
  const steeringWheel = document.getElementById('steering-wheel');
  const cursor = document.getElementById('cursor');
  const centerX = steeringWheel.offsetWidth / 2;
  const centerY = steeringWheel.offsetHeight / 2;
  let mouseX = 0;
  let mouseY = 0;
  function updateCursorPosition(event) {
    const mouseXRelative = event.clientX - steeringWheel.offsetLeft;
    const mouseYRelative = event.clientY - steeringWheel.offsetTop;
    const angle = Math.atan2(mouseYRelative - centerY, mouseXRelative - centerX);
    cursor.style.left = `${mouseXRelative - 5}px`;
    cursor.style.top = `${mouseYRelative - 5}px`;
    steeringWheel.style.transform = `rotate(${(angle * 450 / Math.PI)}deg)`;
  }
  window.addEventListener('mousemove', (event) => {
    updateCursorPosition(event);
  });
}
steering()


// for Page 3
function page3Animation() {
  gsap.to(".page3", {
    scrollTrigger: {
      trigger: ".page3",
      scroller: "main",
      start: "top top",
      end: "bottom top",
      pin: true,
      scrub: true
    }
  });
}


function page4Animation() {
  gsap.to(".page4", {
    scrollTrigger: {
      trigger: ".page4",
      scroller: "main",
      start: "top top",
      end: "bottom top",
      pin: true,
      scrub: true
    }
  });
}

function page5Animation() {
  gsap.to(".page5", {
    scrollTrigger: {
      trigger: ".page5",
      scroller: "main",
      start: "top top",
      end: "bottom top",
      pin: true,
      scrub: true
    }
  });
}

function page51Animation() {
  gsap.to(".page5-1", {
    scrollTrigger: {
      trigger: ".page5-1",
      scroller: "main",
      start: "top top",
      end: "bottom top",
      pin: true,
      scrub: true
    }
  });
}

function page52Animation() {
  gsap.to(".page5-2", {
    scrollTrigger: {
      trigger: ".page5-2",
      scroller: "main",
      start: "top top",
      end: "bottom top",
      pin: true,
      scrub: true
    }
  });
}

page3Animation();
page4Animation();
page5Animation();
page51Animation();
page52Animation();





// for Page 8
function page8mainAnimation() {
  gsap.to(".page8", {
    scrollTrigger: {
      trigger: ".page8",
      scroller: "main",
      start: "top top",
      end: "bottom top",
      pin: true,
      scrub: true
    }
  });
}
page8mainAnimation()
function page8Animation() {
  gsap.to(".page8 .part1 img", {
    scrollTrigger: {
      trigger: ".page8",
      scroller: "main", 
      start: "top center",
      end: "bottom center",
      scrub: true
    },
    x: 100,
    duration: 2,
    ease: "power2.out"
  });
}
page8Animation()
function page8charging() {
  gsap.to(".page8 .part2", {
    scrollTrigger: {
      trigger: ".page8",
      scroller: "main",
      start: "top center", 
      end: "bottom center",
      scrub: true
    },
    y: 0,
    opacity: 1,
    duration: 2,
    ease: "power2.out"
  });
}
page8charging()
function page8Part1Animation() {
  gsap.to(".page8 .part3 .img1", {
    scrollTrigger: {
      trigger: ".page8",
      scroller: "main",
      start: "top center", 
      end: "bottom center",
      scrub: true
    },
    y: 120,
    opacity: 1,
    duration: 2,
    ease: "power2.out"
  });
}
page8Part1Animation()
function page8Part2Animation() {
  gsap.to(".page8 .part3 .img2", {
    scrollTrigger: {
      trigger: ".page8",
      scroller: "main",
      start: "top center", 
      end: "bottom center",
      scrub: true
    },
    x: -220,
    duration: 2,
    ease: "power2.out"
  });
}




// for page 10
function page10Animation() {
  // Pin part1 at top
  gsap.to(".page10 .part1", {
    scrollTrigger: {
      trigger: ".page10",
      scroller: "main",
      start: "top top",
      end: "bottom top",
      pin: true,
      scrub: true
    }
  });

  // Animate part2 scrolling up
  gsap.to(".page10 .part2", {
    scrollTrigger: {
      trigger: ".page10",
      scroller: "main",
      start: "top top",
      end: "bottom top",
      scrub: true
    },
    y: -800,
    opacity: 1,
    duration: 2,
    ease: "power2.out"
  });

  // Animate entire page10 scrolling up at end
  gsap.to(".page10", {
    scrollTrigger: {
      trigger: ".page10",
      scroller: "main",
      start: "bottom center",
      end: "bottom top",
      scrub: true
    },
    y: -100,
    duration: 1,
    ease: "none"
  });
}
page10Animation()



page8Part2Animation()
function page6Animation(){
  gsap.to(".page6", {
    scrollTrigger: {
      trigger: ".page6",
      scroller: "main",
      start: "top top",
      end: "bottom bottom",
      pin: true,
      pinSpacing: false
    }
  });
}

