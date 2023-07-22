/**
 * Logo cloud
 */

document.addEventListener('DOMContentLoaded', () => {

    const bubblesEl = document.querySelector('.bubbles');
    console.log(bubblesEl);
    const bubbleSpecs = JSON.parse(bubblesEl.dataset.coors);
    const canvasWidth = parseFloat(bubblesEl.dataset.canvasWidth);

    const SCROLL_SPEED = 0.3;
    const NOISE_SPEED = 0.004;
    const NOISE_AMOUNT = 5;
    const CANVAS_WIDTH = canvasWidth * 2;

    function Bubbles(specs) {
        var bubbles = [];

        specs.forEach((spec, index) => {
            bubbles.push(Bubble(index, spec));
        })
        
        requestAnimationFrame(BubblesUpdate(bubbles));
    }

    function BubblesUpdate(bubbles) {
        bubbles.forEach(bubble => BubbleUpdate(bubble))
        var raf = requestAnimationFrame(BubblesUpdate(bubbles))
    }

    function Bubble(index, {x, y, s = 1}) {
        var x = x;
        var y = y;
        var scale = s;

        var noiseSeedX = Math.floor(Math.random() * 64000);
        var noiseSeedY = Math.floor(Math.random() * 64000);

        var el = $('.bubble.logo'+(index + 1));

        return {
            index,
            x,
            y,
            scale,
            noiseSeedX,
            noiseSeedY,
            el
        }
    }

    function BubbleUpdate(bubble) {

        bubble.noiseSeedX += NOISE_SPEED;
        bubble.noiseSeedY += NOISE_SPEED;

        let randomX = noise.simplex2(noiseSeedX, 0);
        let randomY = noise.simplex2(noiseSeedY, 0);
        
        bubble.x -= SCROLL_SPEED;

        var xWithNoise = bubble.x + (randomX * NOISE_AMOUNT);
        var yWithNoise = bubble.y + (randomY * NOISE_AMOUNT)
        
        if (bubble.x <  -200) {
            bubble.x = CANVAS_WIDTH;
        }
        
        if(bubble.el) {
            bubble.el.style.transform = `translate(${xWithNoise}px, ${yWithNoise}px) scale(${bubble.scale})`;
        }

    }

    // For perlin noise
    noise.seed(Math.floor(Math.random() * 64000));
    const createBubbles = new Bubbles(bubbleSpecs);

});


