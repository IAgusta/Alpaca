class Star {
    constructor(canvas) {
        this.canvas = canvas;
        this.reset();
    }

    reset() {
        this.x = Math.random() * this.canvas.width;
        this.y = 0;
        this.speed = 1 + Math.random() * 2;
        this.radius = 0.5 + Math.random() * 1.5;
        this.opacity = Math.random();
    }

    update() {
        this.y += this.speed;
        this.opacity -= 0.01;
        
        if (this.y > this.canvas.height || this.opacity <= 0) {
            this.reset();
        }
    }

    draw(ctx) {
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(255, 255, 255, ${this.opacity})`;
        ctx.fill();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('starfield');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    
    const resizeCanvas = () => {
        const container = canvas.parentElement;
        canvas.width = container.offsetWidth;
        canvas.height = container.offsetHeight;
    };

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    const stars = Array(100).fill().map(() => new Star(canvas));

    function animate() {
        ctx.fillStyle = 'rgba(17, 24, 39, 1)'; // Dark background
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        stars.forEach(star => {
            star.update();
            star.draw(ctx);
        });

        requestAnimationFrame(animate);
    }

    animate();
});
