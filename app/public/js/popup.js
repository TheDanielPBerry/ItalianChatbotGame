const CreateOverlay = () => {
	let overlay = document.createElement('div');
	overlay.classList.add('overlay');
	overlay.classList.add('hide');
	return overlay;
};

const CreateModal = (html) => {
	let modal = document.createElement('div');
	modal.classList.add('modal');
	modal.innerHTML = html;

	let overlay = CreateOverlay();
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

const SpawnNotification = (message, timeToClose) => {
	let notification = document.createElement('div');
	notification.classList.add('notification');
	notification.innerHTML = `<div class="modal-body"><h2 class="text-center">${message}</h2></div>`;
	let overlay = CreateOverlay();
	overlay.append(notification);
	document.body.append(overlay);
	overlay.classList.remove('hide');
	setTimeout(() => overlay.remove(), timeToClose);
};

window.addEventListener('load', () => {
	document.body.addEventListener('click', ClickModal);
});
