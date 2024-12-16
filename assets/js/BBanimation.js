document.addEventListener('DOMContentLoaded', () => {
    const root = document.documentElement;
    const container = document.querySelector('.background-slider-overlay');
  
    function setRandomPosition() {
      const maxX = container.clientWidth - 50;
      const maxY = container.clientHeight - 50;
  
      const randomX = Math.floor(Math.random() * maxX) *0.1;
      const randomY = Math.floor(Math.random() * maxY) *0.2;
      console.log(randomX , randomY)
  
      root.style.setProperty('--random-x', `${randomX}px`);
      root.style.setProperty('--random-y', `${randomY}px`);
    }
  
    setRandomPosition(); // initial call
  });