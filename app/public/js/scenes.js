const exitSurvey = (returnScene) => {
	return () => {
		if(document.getElementById('surveyed').value !== '1') {
			OpenModal('survey_modal');
		}
		return returnScene();
	}
};

const scenes = {
	'residential': () => {
		wipe.x = -canvas.x;
		scene = [];
		//Background
		scene.push(new Artifact(null, new Rect(0, 0, null, null), "/img/residential/residential.png"));
		//Interactives
		scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/residential/apartment.png", "Appartamento"));
		scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/residential/grocery.png", "Drogheria", scenes['grocery']));

		clickEvent = () => {
		};
	},
	'grocery': () => {
		wipe.x = -canvas.x;
		scene = [];
		//Background
		scene.push(new Artifact(null, new Rect(0, 0, null, null), "/img/grocery/grocery.png"));
		//Interactives
		scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/grocery/butcher.png", "Macellaio", scenes['butcher']));
		scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/grocery/exit.png", "Uscita", scenes['residential']));
		scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/grocery/cashier.png", "Casse"));

		scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/grocery/vino.png", "Vino"));
		scene.push(new Interactive(null, new Rect(0, 0, null, null), "/img/grocery/formaggio.png", "Formaggio"));

		clickEvent = () => {
		};
	},
	'butcher': () => {
		scene = [];
		scene.push(new Artifact(null, new Rect(0, 0, null, null), "/img/characters/butcher.png"));
		scene.push(new Interactive(null, new Rect(440, 8, null, null), "/img/ciao.png", "Abbandonare la Conversazione", exitSurvey(scenes['grocery'])));
		clickEvent = null;
		LoadChat('Macellaio');
		AddHistory('Ciao!', 'chatbot', 0);
	},
	'clerk': () => {
		scene = [];
		scene.push(new Artifact(null, new Rect(0, 0, null, null), "/img/characters/clerk.png"));
		scene.push(new Interactive(null, new Rect(440, 8, null, null), "/img/ciao.png", "Abbandonare la Conversazione", exitSurvey(scenes['grocery'])));
		clickEvent = null;
		LoadChat('Cassiere');
		AddHistory('Ciao! Com\'era il tuo esperianza oggi?', 'chatbot', 0);
	},
};

