/**
 * Logo cloud
 */

document.addEventListener('DOMContentLoaded', () => {

    const SCROLL_SPEED = 0.3;
    const NOISE_SPEED = 0.004;
    const NOISE_AMOUNT = 5;

    const bubblesEls = document.querySelectorAll('.bubbles');

    class Bubbles {
        constructor(specs, parent) {
            this.bubbles = [];

            specs.forEach((spec, index) => {
                this.bubbles.push(new Bubble(index, spec, parent));
            })
            
            requestAnimationFrame(this.update.bind(this));
        }
    
        update() {
            this.bubbles.forEach(bubble => bubble.update());
            this.raf = requestAnimationFrame(this.update.bind(this))
        }  
    }


    class Bubble {
        constructor(index, {x, y, s = 1}, parent) {
            this.index = index;
            this.x = x;
            this.y = y;
            this.scale = s;
            this.parent = parent

            this.noiseSeedX = Math.floor(Math.random() * 64000);
            this.noiseSeedY = Math.floor(Math.random() * 64000);

            this.el = parent.querySelector(`.bubble.logo${this.index + 1}`);
        }
    
        update() {
            this.noiseSeedX += NOISE_SPEED;
            this.noiseSeedY += NOISE_SPEED;
            let randomX = noise.simplex2(this.noiseSeedX, 0);
            let randomY = noise.simplex2(this.noiseSeedY, 0);
            
            this.x -= SCROLL_SPEED;
            this.xWithNoise = this.x + (randomX * NOISE_AMOUNT);
            this.yWithNoise = this.y + (randomY * NOISE_AMOUNT)
            
            if (this.x < -200) {
                this.x = parseFloat(this.parent.dataset.canvasWidth) * 2;
            }
            
            if(this.el) {
                this.el.style.transform = `translate(${this.xWithNoise}px, ${this.yWithNoise}px) scale(${this.scale})`;
            }
        }
    }

    bubblesEls.forEach(bubblesEl => {
        const parent = bubblesEl
        const bubbleSpecs = JSON.parse(bubblesEl.dataset.coors);

        // For perlin noise
        noise.seed(Math.floor(Math.random() * 64000));
        const createBubbles = new Bubbles(bubbleSpecs, parent);
    });

});


