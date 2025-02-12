//Global Game context
var canvas = document.getElementById('canvas');
var ctx = canvas.getContext('2d');
var focus = null;
var scene = [];
var clickEvent = null;
var wipe;
var mousePos;

class Point {
	constructor(x, y) {
		this.x = x;
		this.y = y;
	}
}
class Rect {
	constructor(x, y, w, h) {
		this.x = x;
		this.y = y;
		this.w = w;
		this.h = h;
	}
	contains(point) {
		return point.x > this.x && point.x < this.x + this.w && point.y > this.y && point.y < this.y + this.h;
	}
}

class Artifact {
	constructor(src, dest, imagePath) {
		this.image = new Image();
		this.image.src = imagePath;
		this.dest = dest;
		this.src = src;
	}
	draw(ctx) {
		if(this.src == null) {
			if(this.dest.w == null || this.dest.h == null) {
				ctx.drawImage(this.image, this.dest.x, this.dest.y, this.image.width, this.image.height);
			} else {
				ctx.drawImage(this.image, this.dest.x, this.dest.y, this.dest.w, this.dest.h);
			}
		} else {
			ctx.drawImage(this.image, this.src.x, this.src.y, this.src.w, this.src.h, this.dest.x, this.dest.y, this.dest.w, this.dest.h);
		}
	}
}

class Interactive extends Artifact {
	constructor(src, dest, imagePath, name, event) {
		super(src, dest, imagePath);
		let tempCanvas = document.createElement('canvas');
		tempCanvas.width = 800;
		tempCanvas.height = 600;
		this.imageContext = tempCanvas.getContext('2d', { willReadFrequently: true });
		this.image.onload = () => this.draw(this.imageContext);
		this.name = name;
		this.onClick = event;
	}
	intersectsPixel(pos) {
		let pixel = this.imageContext.getImageData(pos.x, pos.y, 1, 1);
		return pixel.data[3] !== 0;
	}
}

let inventory = {
	prosciutto: false,
	vino: false,
	taleggio: false,
};


function LoadResidential() {
	wipe.x = -canvas.x;
	scene = [];
	scene.push(new Artifact(null, new Rect(0, 0, null, null), "/img/residential.png"));
	scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/apartment.png", "Appartamento"));
	scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/grocery.png", "Drogheria", () => LoadGrocery()));

	clickEvent = () => {
	};
}

function LoadGrocery() {
	wipe.x = -canvas.x;
	scene = [];
	scene.push(new Artifact(null, new Rect(0, 0, null, null), "/img/grocery/grocery.png"));
	scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/grocery/butcher.png", "Macellaio"));
	scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/grocery/vino.png", "Vino"));
	scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/grocery/exit.png", "Uscita", () => LoadResidential()));
	scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/grocery/cashier.png", "Casse"));
	scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/grocery/formaggio.png", "Formaggio"));
	clickEvent = () => {
	};
}

function DrawScene() {
	ctx.fillStyle='#FFFFFF';
	ctx.fillRect(0, 0, canvas.width, canvas.height);

	scene.forEach((artifact) => {
		if(artifact == focus) {
			ctx.globalAlpha = 0.7;
		} else {
			ctx.globalAlpha = 1.0;
		}
		artifact.draw(ctx);
	});
	if(typeof(focus) !== 'undefined') {
		ctx.globalAlpha = 0.8;
		let label = focus.name;
		ctx.font = '20px Georgia';
		ctx.fillStyle = '#000';
		ctx.fillRect(mousePos.x+5, mousePos.y - 15, label.length*10.5, 28);
		ctx.fillStyle = '#FFF';
		ctx.fillText(label, mousePos.x + 8, mousePos.y + 5);
	}

	ctx.fillStyle='#EEEEEE';
	ctx.fillRect(wipe.x, wipe.y, canvas.width, canvas.height);
	if(wipe.x !== 0 && wipe.x <= canvas.width) {
		wipe.x += 10;
	}
}

function InitializeGame() {
	wipe = new Point(-canvas.width, 0);
	LoadResidential();
}

function GetInteractiveArtifact(pos) {
	return scene.find((artifact) => {
		if(artifact instanceof Interactive) {
			if(artifact.intersectsPixel(pos)) {
				return true;
			}
		}
	});
}


canvas.addEventListener('mousemove', (e) => {
	mousePos = new Point(e.offsetX, e.offsetY);
	focus = GetInteractiveArtifact(mousePos);
	if(typeof(focus) !== 'undefined') {
		canvas.style.cursor = 'pointer';
	} else {
		canvas.style.cursor = '';
	}
});
canvas.addEventListener('click', (e) => {
	if(typeof(focus) === 'undefined') {
		return;
	}
	if(typeof(focus.onClick) !== 'undefined') {
		focus.onClick();
	} else if(typeof(clickEvent) === 'function') {
		clickEvent();
	}
	focus = null;
});

InitializeGame();
setInterval(() => DrawScene(), 50);
