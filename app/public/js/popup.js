const CreateModal = (html) => {
	let modal = document.createElement('div');
	modal.classList.add('modal');
	modal.innerHTML = html;

	let overlay = document.createElement('div');
	overlay.classList.add('overlay');
	overlay.classList.add('hide');
	overlay.appendChild(modal);
	document.body.appendChild(overlay);
};

const OpenModal = (id) => document.getElementById(id).classList.remove('hide');
const CloseModal = (id) => document.getElementById(id).classList.add('hide');

const ClickModal = (e) => {
	if(e.target === null) {
		return;
	}
	let overlay = e.target.closest('.overlay');
	if(overlay === null) {
		return;
	}
	if(e.target.classList.contains('overlay') || e.target.classList.contains('modal-close')) {
		CloseModal(overlay.id);
		return;
	}
};

window.addEventListener('load', () => {
	document.body.addEventListener('click', ClickModal);
});
