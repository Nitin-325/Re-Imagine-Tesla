
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